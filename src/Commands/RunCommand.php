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
                            {--api : API wrapper for Laravel}
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
        if ($this->option('api')) {
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

            $this->info("API published successfully.");
        }

        if ($this->option('vue')) {
            if ($this->confirm('Some files will overwrite. Do you wish to continue?', 'yes')) {
                $this->createVueDirectories();
                $this->createVueFiles();

                file_put_contents(
                    base_path("resources/assets/sass/app.scss"),
                    file_get_contents(__DIR__ . "/stubs/vue/sass/style.stub")
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
                    base_path('resources/assets/js/bootstrap.js'),
                    file_get_contents(__DIR__ . "/stubs/vue/bootstrap.stub")
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

                $json->scripts->{"eslint"} = "node_modules/.bin/eslint --ext .js,.vue resources/assets/js";

                if (!isset($json->dependencies)) {
                    $json->dependencies = (object)[];
                }

                if (!isset($json->devDependencies)) {
                    $json->devDependencies = (object)[];
                }

                $json->dependencies->{"vue"} = "^2.5.13";
                $json->dependencies->{"vuex"} = "^3.0.1";
                $json->dependencies->{"vue-router"} = "^3.0.1";
                $json->dependencies->{"vue-i18n"} = "^7.3.3";
                $json->dependencies->{"vuex-router-sync"} = "^5.0.0";
                $json->dependencies->{"vee-validate"} = "^2.0.0";
                $json->dependencies->{"vue-meta"} = "^1.4.0";
                $json->dependencies->{"axios"} = "^0.17.1";
                $json->dependencies->{"cxlt-vue2-toastr"} = "^1.0.12";
                $json->dependencies->{"lodash"} = "^4.17.4";
                $json->dependencies->{"jquery"} = "^3.1.1";
                $json->dependencies->{"bootstrap"} = "^4.0.0";
                $json->dependencies->{"popper.js"} = "^1.12.9";
                $json->dependencies->{"js-cookie"} = "^2.2.0";
                $json->dependencies->{"schepotin-vuex-helpers"} = "^0.0.5";

                $json->devDependencies->{"babel-preset-es2015"} = "^6.24.1";
                $json->devDependencies->{"babel-preset-es2016"} = "^6.24.1";
                $json->devDependencies->{"babel-preset-es2017"} = "^6.24.1";
                $json->devDependencies->{"babel-plugin-syntax-dynamic-import"} =  "^6.18.0";
                $json->devDependencies->{"babel-plugin-transform-runtime"} = "^6.23.0";

                $json->devDependencies->{"eslint"} = "^3.19.0";
                $json->devDependencies->{"babel-eslint"} = "^7.1.1";
                $json->devDependencies->{"eslint-config-airbnb-base"} = "^11.1.3";
                $json->devDependencies->{"eslint-friendly-formatter"} = "^3.0.0";
                $json->devDependencies->{"eslint-import-resolver-webpack"} = "^0.8.1";
                $json->devDependencies->{"eslint-loader"} = "^1.7.1";
                $json->devDependencies->{"eslint-plugin-html"} = "^3.0.0";
                $json->devDependencies->{"eslint-plugin-import"} =  "^2.2.0";

                unset($json->devDependencies->{"vue"});
                unset($json->devDependencies->{"axios"});
                unset($json->devDependencies->{"jquery"});
                unset($json->devDependencies->{"bootstrap-sass"});
                unset($json->devDependencies->{"lodash"});

                file_put_contents(
                    base_path('package.json'),
                    (preg_replace(
                        '/^(  +?)\\1(?=[^ ])/m', // Replace 4 space to 2 space
                        '$1',
                        json_encode($json, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT))
                    )
                );

                file_put_contents(
                    base_path('webpack.mix.js'),
                    file_get_contents(__DIR__ . "/stubs/vue/webpack.mix.stub")
                );

                if (!file_exists(base_path('.babelrc'))) {
                    file_put_contents(
                        base_path('.babelrc'),
                        file_get_contents(__DIR__ . "/stubs/vue/babelrc.stub")
                    );
                }

                if (!file_exists(base_path('.eslintrc'))) {
                    file_put_contents(
                        base_path('.eslintrc.js'),
                        file_get_contents(__DIR__ . "/stubs/vue/eslintrc.stub")
                    );
                }
            }

            $this->info("Vue published successfully.");
        }
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
        if (!is_dir(base_path("resources/views/layouts"))) {
            mkdir(base_path("resources/views/layouts"), 0755, true);
        }

        if (!is_dir(base_path("resources/assets/js/api"))) {
            mkdir(base_path("resources/assets/js/api"), 0755, true);
        }

        if (!is_dir(base_path("resources/assets/js/plugins"))) {
            mkdir(base_path("resources/assets/js/plugins"), 0755, true);
        }

        if (!is_dir(base_path("resources/assets/js/components"))) {
            mkdir(base_path("resources/assets/js/components"), 0755, true);
        }

        if (!is_dir(base_path("resources/assets/js/utils"))) {
            mkdir(base_path("resources/assets/js/utils"), 0755, true);
        }

        if (!is_dir(base_path("resources/assets/js/pages"))) {
            mkdir(base_path("resources/assets/js/pages"), 0755, true);
        }

        if (!is_dir(base_path("resources/assets/js/pages/auth"))) {
            mkdir(base_path("resources/assets/js/pages/auth"), 0755, true);
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

        if (!is_dir(base_path("resources/assets/js/store/modules/user"))) {
            mkdir(base_path("resources/assets/js/store/modules/user"), 0755, true);
        }

        if (!is_dir(base_path("resources/assets/js/mixins"))) {
            mkdir(base_path("resources/assets/js/mixins"), 0755, true);
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

        if (!file_exists(base_path('resources/assets/js/components/.gitkeep'))) {
            file_put_contents(
                base_path('resources/assets/js/components/.gitkeep'),
                file_get_contents(__DIR__ . "/stubs/vue/components/.gitkeep")
            );
        }

        if (!file_exists(base_path('resources/assets/js/utils/.gitkeep'))) {
            file_put_contents(
                base_path('resources/assets/js/utils/.gitkeep'),
                file_get_contents(__DIR__ . "/stubs/vue/utils/.gitkeep")
            );
        }

        if (!file_exists(base_path('resources/assets/js/pages/NotFound.vue'))) {
            file_put_contents(
                base_path('resources/assets/js/pages/NotFound.vue'),
                file_get_contents(__DIR__ . "/stubs/vue/pages/NotFound.stub")
            );
        }

        if (!file_exists(base_path('resources/assets/js/pages/Error.vue'))) {
            file_put_contents(
                base_path('resources/assets/js/pages/Error.vue'),
                file_get_contents(__DIR__ . "/stubs/vue/pages/Error.stub")
            );
        }

        if (!file_exists(base_path('resources/assets/js/pages/Loading.vue'))) {
            file_put_contents(
                base_path('resources/assets/js/pages/Loading.vue'),
                file_get_contents(__DIR__ . "/stubs/vue/pages/Loading.stub")
            );
        }

        if (!file_exists(base_path('resources/assets/js/pages/Home.vue'))) {
            file_put_contents(
                base_path('resources/assets/js/pages/Home.vue'),
                file_get_contents(__DIR__ . "/stubs/vue/pages/Home.stub")
            );
        }

        if (!file_exists(base_path('resources/assets/js/pages/App.vue'))) {
            file_put_contents(
                base_path('resources/assets/js/pages/App.vue'),
                file_get_contents(__DIR__ . "/stubs/vue/pages/App.stub")
            );
        }

        if (!file_exists(base_path('resources/assets/js/pages/auth/Login.vue'))) {
            file_put_contents(
                base_path('resources/assets/js/pages/auth/Login.vue'),
                file_get_contents(__DIR__ . "/stubs/vue/pages/auth/Login.stub")
            );
        }

        if (!file_exists(base_path('resources/assets/js/pages/auth/Register.vue'))) {
            file_put_contents(
                base_path('resources/assets/js/pages/auth/Register.vue'),
                file_get_contents(__DIR__ . "/stubs/vue/pages/auth/Register.stub")
            );
        }

        if (!file_exists(base_path('resources/assets/js/pages/auth/PasswordEmail.vue'))) {
            file_put_contents(
                base_path('resources/assets/js/pages/auth/PasswordEmail.vue'),
                file_get_contents(__DIR__ . "/stubs/vue/pages/auth/PasswordEmail.stub")
            );
        }

        if (!file_exists(base_path('resources/assets/js/pages/auth/ResetPassword.vue'))) {
            file_put_contents(
                base_path('resources/assets/js/pages/auth/ResetPassword.vue'),
                file_get_contents(__DIR__ . "/stubs/vue/pages/auth/ResetPassword.stub")
            );
        }

        if (!file_exists(base_path('resources/assets/js/pages/auth/Profile.vue'))) {
            file_put_contents(
                base_path('resources/assets/js/pages/auth/Profile.vue'),
                file_get_contents(__DIR__ . "/stubs/vue/pages/auth/Profile.stub")
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

        if (!file_exists(base_path('resources/assets/js/store/modules/user/index.js'))) {
            file_put_contents(
                base_path('resources/assets/js/store/modules/user/index.js'),
                file_get_contents(__DIR__ . "/stubs/vue/store/modules/user/index.stub")
            );
        }

        if (!file_exists(base_path('resources/assets/js/store/modules/user/actions.js'))) {
            file_put_contents(
                base_path('resources/assets/js/store/modules/user/actions.js'),
                file_get_contents(__DIR__ . "/stubs/vue/store/modules/user/actions.stub")
            );
        }

        if (!file_exists(base_path('resources/assets/js/store/modules/user/getters.js'))) {
            file_put_contents(
                base_path('resources/assets/js/store/modules/user/getters.js'),
                file_get_contents(__DIR__ . "/stubs/vue/store/modules/user/getters.stub")
            );
        }

        if (!file_exists(base_path('resources/assets/js/store/modules/user/mutations.js'))) {
            file_put_contents(
                base_path('resources/assets/js/store/modules/user/mutations.js'),
                file_get_contents(__DIR__ . "/stubs/vue/store/modules/user/mutations.stub")
            );
        }

        if (!file_exists(base_path('resources/assets/js/store/modules/user/state.js'))) {
            file_put_contents(
                base_path('resources/assets/js/store/modules/user/state.js'),
                file_get_contents(__DIR__ . "/stubs/vue/store/modules/user/state.stub")
            );
        }

        if (!file_exists(base_path('resources/assets/js/modules/user/store/mutation-types.js'))) {
            file_put_contents(
                base_path('resources/assets/js/store/modules/user/mutation-types.js'),
                file_get_contents(__DIR__ . "/stubs/vue/store/modules/user/mutation-types.stub")
            );
        }

        if (!file_exists(base_path('resources/assets/js/mixins/user.js'))) {
            file_put_contents(
                base_path('resources/assets/js/mixins/user.js'),
                file_get_contents(__DIR__ . "/stubs/vue/mixins/user.stub")
            );
        }

        if (!file_exists(base_path('resources/assets/js/plugins/lang.js'))) {
            file_put_contents(
                base_path('resources/assets/js/plugins/lang.js'),
                file_get_contents(__DIR__ . "/stubs/vue/plugins/lang.stub")
            );
        }

        if (!file_exists(base_path('resources/assets/js/plugins/meta.js'))) {
            file_put_contents(
                base_path('resources/assets/js/plugins/meta.js'),
                file_get_contents(__DIR__ . "/stubs/vue/plugins/meta.stub")
            );
        }

        if (!file_exists(base_path('resources/assets/js/plugins/toastr.js'))) {
            file_put_contents(
                base_path('resources/assets/js/plugins/toastr.js'),
                file_get_contents(__DIR__ . "/stubs/vue/plugins/toastr.stub")
            );
        }

        if (!file_exists(base_path('resources/assets/js/plugins/validator.js'))) {
            file_put_contents(
                base_path('resources/assets/js/plugins/validator.js'),
                file_get_contents(__DIR__ . "/stubs/vue/plugins/validator.stub")
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