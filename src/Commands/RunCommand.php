<?php

namespace Schepotin\LaravelApi\Commands;

use PhpParser\Error;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter;
use Illuminate\Console\Command;
use Illuminate\Console\DetectsApplicationNamespace;

class RunCommand extends Command
{
    use DetectsApplicationNamespace;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "laravel-api:publish";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Publish API auth files";

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->createDirectories();

        /*
         * Add value 'api_token' to arrays $fillable and $hidden
         */
        $user_model_code = file_get_contents(app_path("User.php"));
        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);

        try {
            $prettyPrinter = new PrettyPrinter\Standard();
            $stmts = $parser->parse($user_model_code);

            if (isset($stmts[0]->stmts[2]->stmts)) {
                foreach ($stmts[0]->stmts[2]->stmts as $stmt) {
                    if (isset($stmt->props[0]->name) && ($stmt->props[0]->name == "fillable" || $stmt->props[0]->name == "hidden")) {
                        if (isset($stmt->props[0]->default->items)) {
                            $is_update = true;

                            foreach ($stmt->props[0]->default->items as $item) {
                                if (isset($item->value->value) && $item->value->value == "api_token") {
                                    $is_update = false;
                                }
                            }

                            if ($is_update == true) {
                                $stmt->props[0]
                                    ->default
                                    ->items[] = new \PhpParser\Node\Expr\ArrayItem(new \PhpParser\Node\Scalar\String_("api_token"));
                            }
                        }
                    }
                }
            }

            file_put_contents(app_path("User.php"), $prettyPrinter->prettyPrintFile($stmts));
        } catch (Error $e) {
            echo "Parse Error: ", $e->getMessage();
        }

        /*
         * Insert 'api_token' => str_random(60) into method create
         */
        $register_controller_code = file_get_contents(app_path("Http/Controllers/Auth/RegisterController.php"));
        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);

        try {
            $prettyPrinter = new PrettyPrinter\Standard();
            $stmts = $parser->parse($register_controller_code);

            if (isset($stmts[0]->stmts[4]->stmts)) {
                foreach ($stmts[0]->stmts[4]->stmts as $stmt) {
                    if (isset($stmt->name) && $stmt->name == "create") {
                        if (isset($stmt->stmts[0]->expr->args[0]->value->items)) {
                            $is_update = true;

                            foreach ($stmt->stmts[0]->expr->args[0]->value->items as $item) {
                                if (isset($item->key->value) && $item->key->value == "api_token") {
                                    $is_update = false;
                                }
                            }

                            if ($is_update == true) {
                                $stmt->stmts[0]
                                    ->expr
                                    ->args[0]
                                    ->value
                                    ->items[] = new \PhpParser\Node\Expr\ArrayItem(
                                    new \PhpParser\Node\Expr\FuncCall(
                                        new \PhpParser\Node\Name("str_random"),
                                        [new \PhpParser\Node\Arg(new \PhpParser\Node\Scalar\LNumber(60))]
                                    ),
                                    new \PhpParser\Node\Scalar\String_("api_token")
                                );
                            }
                        }
                    }
                }
            }

            file_put_contents(app_path("Http/Controllers/Auth/RegisterController.php"), $prettyPrinter->prettyPrintFile($stmts));
        } catch (Error $e) {
            echo "Parse Error: ", $e->getMessage();
        }

        if (!file_exists(app_path("Http/Controllers/Api/V1/UserController.php"))) {
            file_put_contents(
                app_path("Http/Controllers/Api/V1/UserController.php"),
                $this->compileControllerStub()
            );
        }

        if (!file_exists(database_path("migrations/2017_07_18_000000_users_add_api_token_column.php"))) {
            file_put_contents(
                database_path("migrations/2017_07_18_000000_users_add_api_token_column.php"),
                file_get_contents(__DIR__ . "/stubs/migrations/2017_07_18_000000_users_add_api_token_column.stub")
            );
        }

        file_put_contents(
            base_path("routes/api.php"),
            file_get_contents(__DIR__ . "/stubs/routes/api.stub"),
            FILE_APPEND
        );

        $this->info("Published successfully.");
    }

    /**
     * Create the directories for the files.
     *
     * @return void
     */
    protected function createDirectories()
    {
        if (!is_dir(app_path("Http/Controllers/Api/V1"))) {
            mkdir(app_path("Http/Controllers/Api/V1"), 0755, true);
        }
    }

    protected function compileControllerStub()
    {
        return str_replace(
            "{{namespace}}",
            $this->getAppNamespace(),
            file_get_contents(__DIR__ . "/stubs/Controllers/UserController.stub")
        );
    }
}