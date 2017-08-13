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
    protected $signature = "laravel-api:publish
                            {--vue : Boilerplate for Vue}";

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
        if ($this->option('vue')) {
            if ($this->confirm('Some files will overwrite. Do you wish to continue?')) {
                $this->createVueDirectories();
                $this->createVueFiles();

                file_put_contents(
                    base_path("resources/assets/sass/app.scss"),
                    file_get_contents(__DIR__ . "/stubs/vue/sass/style.stub"),
                    FILE_APPEND
                );

                if (!file_exists(app_path("Http/Controllers/SpaController.php"))) {
                    file_put_contents(
                        app_path("Http/Controllers/SpaController.php"),
                        $this->compileSpaControllerStub()
                    );
                }

                file_put_contents(
                    base_path("routes/web.php"),
                    file_get_contents(__DIR__ . "/stubs/routes/web.stub")
                );

                file_put_contents(
                    base_path('resources/assets/js/app.js'),
                    file_get_contents(__DIR__ . "/stubs/vue/app.stub")
                );

                file_put_contents(
                    base_path('resources/assets/js/app.js'),
                    file_get_contents(__DIR__ . "/stubs/vue/app.stub")
                );

                file_put_contents(
                    base_path('resources/views/layouts/app.blade.php'),
                    file_get_contents(__DIR__ . "/stubs/vue/views/layouts/app.blade.stub")
                );

                file_put_contents(
                    base_path('resources/views/home.blade.php'),
                    file_get_contents(__DIR__ . "/stubs/vue/views/home.blade.stub")
                );

                $json = json_decode(file_get_contents(base_path('package.json')));

                $json->devDependencies->{"babel-preset-es2015"} = "^6.24.1";
                $json->devDependencies->{"babel-preset-es2016"} = "^6.24.1";
                $json->devDependencies->{"babel-preset-es2017"} = "^6.24.1";
                $json->devDependencies->{"babel-plugin-transform-runtime"} = "^6.23.0";
                $json->devDependencies->{"vue"} = "^2.4.2";
                $json->devDependencies->{"vuex"} = "^2.3.1";
                $json->devDependencies->{"vue-router"} = "^2.7.0";
                $json->devDependencies->{"vue-i18n"} = "^6.1.1";
                $json->devDependencies->{"vuex-router-sync"} = "^4.1.2";
                $json->devDependencies->{"vee-validate"} = "^2.0.0-rc.10";
                $json->devDependencies->{"axios"} = "^0.16.2";
                $json->devDependencies->{"cxlt-vue2-toastr"} = "^1.0.12";

                file_put_contents(
                    base_path('package.json'),
                    (preg_replace(
                        '/^(  +?)\\1(?=[^ ])/m', // Replace 4 space to 2 space
                        '$1',
                        json_encode($json, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT))
                    )
                );

                if (!file_exists(base_path('.babelrc'))) {
                    file_put_contents(
                        base_path('.babelrc'),
                        file_get_contents(__DIR__ . "/stubs/vue/babelrc.stub")
                    );
                }
            }
        } else {
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
        }

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

    /**
     * Create the directories for the Vue files.
     *
     * @return void
     */
    protected function createVueDirectories()
    {
        if (!is_dir(base_path("resources/assets/js/api"))) {
            mkdir(base_path("resources/assets/js/api"), 0755, true);
        }

        if (!is_dir(base_path("resources/assets/js/components"))) {
            mkdir(base_path("resources/assets/js/components"), 0755, true);
        }

        if (!is_dir(base_path("resources/assets/js/components/auth"))) {
            mkdir(base_path("resources/assets/js/components/auth"), 0755, true);
        }

        if (!is_dir(base_path("resources/assets/js/lang"))) {
            mkdir(base_path("resources/assets/js/lang"), 0755, true);
        }

        if (!is_dir(base_path("resources/assets/js/lang/en"))) {
            mkdir(base_path("resources/assets/js/lang/en"), 0755, true);
        }

        if (!is_dir(base_path("resources/assets/js/lang/he"))) {
            mkdir(base_path("resources/assets/js/lang/he"), 0755, true);
        }

        if (!is_dir(base_path("resources/assets/js/router"))) {
            mkdir(base_path("resources/assets/js/router"), 0755, true);
        }

        if (!is_dir(base_path("resources/assets/js/store"))) {
            mkdir(base_path("resources/assets/js/store"), 0755, true);
        }

        if (!is_dir(base_path("resources/assets/js/store/modules"))) {
            mkdir(base_path("resources/assets/js/store/modules"), 0755, true);
        }
    }

    /**
     * Create the Vue files.
     *
     * @return void
     */
    protected function createVueFiles()
    {
        if (!file_exists(base_path('resources/assets/js/api/user.js'))) {
            file_put_contents(
                base_path('resources/assets/js/api/user.js'),
                file_get_contents(__DIR__ . "/stubs/vue/api/user.stub")
            );
        }

        if (!file_exists(base_path('resources/assets/js/components/index.js'))) {
            file_put_contents(
                base_path('resources/assets/js/components/index.js'),
                file_get_contents(__DIR__ . "/stubs/vue/components/index.stub")
            );
        }

        if (!file_exists(base_path('resources/assets/js/components/Home.vue'))) {
            file_put_contents(
                base_path('resources/assets/js/components/Home.vue'),
                file_get_contents(__DIR__ . "/stubs/vue/components/Home.stub")
            );
        }

        if (!file_exists(base_path('resources/assets/js/components/App.vue'))) {
            file_put_contents(
                base_path('resources/assets/js/components/App.vue'),
                file_get_contents(__DIR__ . "/stubs/vue/components/App.stub")
            );
        }

        if (!file_exists(base_path('resources/assets/js/components/auth/Login.vue'))) {
            file_put_contents(
                base_path('resources/assets/js/components/auth/Login.vue'),
                file_get_contents(__DIR__ . "/stubs/vue/components/auth/Login.stub")
            );
        }

        if (!file_exists(base_path('resources/assets/js/components/auth/Register.vue'))) {
            file_put_contents(
                base_path('resources/assets/js/components/auth/Register.vue'),
                file_get_contents(__DIR__ . "/stubs/vue/components/auth/Register.stub")
            );
        }

        if (!file_exists(base_path('resources/assets/js/components/auth/PasswordEmail.vue'))) {
            file_put_contents(
                base_path('resources/assets/js/components/auth/PasswordEmail.vue'),
                file_get_contents(__DIR__ . "/stubs/vue/components/auth/PasswordEmail.stub")
            );
        }

        if (!file_exists(base_path('resources/assets/js/components/auth/ResetPassword.vue'))) {
            file_put_contents(
                base_path('resources/assets/js/components/auth/ResetPassword.vue'),
                file_get_contents(__DIR__ . "/stubs/vue/components/auth/ResetPassword.stub")
            );
        }

        if (!file_exists(base_path('resources/assets/js/lang/index.js'))) {
            file_put_contents(
                base_path('resources/assets/js/lang/index.js'),
                file_get_contents(__DIR__ . "/stubs/vue/lang/index.stub")
            );
        }

        if (!file_exists(base_path('resources/assets/js/lang/en/en.js'))) {
            file_put_contents(
                base_path('resources/assets/js/lang/en/en.js'),
                file_get_contents(__DIR__ . "/stubs/vue/lang/en/en.stub")
            );
        }

        if (!file_exists(base_path('resources/assets/js/lang/en/validator.js'))) {
            file_put_contents(
                base_path('resources/assets/js/lang/en/validator.js'),
                file_get_contents(__DIR__ . "/stubs/vue/lang/en/validator.stub")
            );
        }

        if (!file_exists(base_path('resources/assets/js/lang/he/he.js'))) {
            file_put_contents(
                base_path('resources/assets/js/lang/he/he.js'),
                file_get_contents(__DIR__ . "/stubs/vue/lang/he/he.stub")
            );
        }

        if (!file_exists(base_path('resources/assets/js/lang/he/validator.js'))) {
            file_put_contents(
                base_path('resources/assets/js/lang/he/validator.js'),
                file_get_contents(__DIR__ . "/stubs/vue/lang/he/validator.stub")
            );
        }

        if (!file_exists(base_path('resources/assets/js/router/index.js'))) {
            file_put_contents(
                base_path('resources/assets/js/router/index.js'),
                file_get_contents(__DIR__ . "/stubs/vue/router/index.stub")
            );
        }

        if (!file_exists(base_path('resources/assets/js/store/index.js'))) {
            file_put_contents(
                base_path('resources/assets/js/store/index.js'),
                file_get_contents(__DIR__ . "/stubs/vue/store/index.stub")
            );
        }

        if (!file_exists(base_path('resources/assets/js/store/modules/user.js'))) {
            file_put_contents(
                base_path('resources/assets/js/store/modules/user.js'),
                file_get_contents(__DIR__ . "/stubs/vue/store/modules/user.stub")
            );
        }

        if (!file_exists(base_path('resources/assets/js/store/actions.js'))) {
            file_put_contents(
                base_path('resources/assets/js/store/actions.js'),
                file_get_contents(__DIR__ . "/stubs/vue/store/actions.stub")
            );
        }

        if (!file_exists(base_path('resources/assets/js/store/getters.js'))) {
            file_put_contents(
                base_path('resources/assets/js/store/getters.js'),
                file_get_contents(__DIR__ . "/stubs/vue/store/getters.stub")
            );
        }

        if (!file_exists(base_path('resources/assets/js/store/mutation-types.js'))) {
            file_put_contents(
                base_path('resources/assets/js/store/mutation-types.js'),
                file_get_contents(__DIR__ . "/stubs/vue/store/mutation-types.stub")
            );
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

    protected function compileSpaControllerStub()
    {
        return str_replace(
            "{{namespace}}",
            $this->getAppNamespace(),
            file_get_contents(__DIR__ . "/stubs/Controllers/SpaController.stub")
        );
    }
}