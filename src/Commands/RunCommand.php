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
        $user_model = new \ReflectionClass(config('auth.providers.users.model'));

        if ($this->option('api')) {
            $this->createDirectories();

            /*
             * Add value 'api_token' to arrays $fillable and $hidden
             */
            $user_model_code = file_get_contents($user_model->getFileName());
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

                file_put_contents($user_model->getFileName(), $prettyPrinter->prettyPrintFile($stmts));
            } catch (Error $e) {
                echo "Parse Error: ", $e->getMessage();
            }

            if (!file_exists(app_path("Http/Controllers/Api/V1/AuthController.php"))) {
                file_put_contents(
                    app_path("Http/Controllers/Api/V1/AuthController.php"),
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
                    base_path("resources/sass/app.scss"),
                    file_get_contents(__DIR__ . "/stubs/vue/sass/style.scss")
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
                    base_path('resources/js/app.js'),
                    file_get_contents(__DIR__ . "/stubs/vue/app.js")
                );

                file_put_contents(
                    base_path('resources/js/bootstrap.js'),
                    file_get_contents(__DIR__ . "/stubs/vue/bootstrap.js")
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

                $json->scripts->{"eslint"} = "node_modules/.bin/eslint --ext .js,.vue resources/js";

                if (!isset($json->dependencies)) {
                    $json->dependencies = (object)[];
                }

                if (!isset($json->devDependencies)) {
                    $json->devDependencies = (object)[];
                }

                $json->dependencies->{"vue"} = "^2.5.16";
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
                $json->dependencies->{"schepotin-vuex-helpers"} = "^0.0.7";

                $json->devDependencies->{"babel-preset-env"} = "^1.7.0";
                $json->devDependencies->{"babel-preset-stage-2"} = "^6.24.1";
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
                $json->devDependencies->{"postcss-rtl"} =  "^1.2.3";

                unset($json->devDependencies->{"vue"});
                unset($json->devDependencies->{"axios"});
                unset($json->devDependencies->{"jquery"});
                unset($json->devDependencies->{"bootstrap-sass"});
                unset($json->devDependencies->{"bootstrap"});
                unset($json->devDependencies->{"popper.js"});
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
                    file_get_contents(__DIR__ . "/stubs/vue/webpack.mix.js")
                );

                if (!file_exists(base_path('.babelrc'))) {
                    file_put_contents(
                        base_path('.babelrc'),
                        file_get_contents(__DIR__ . "/stubs/vue/.babelrc")
                    );
                }

                if (!file_exists(base_path('.eslintrc.js'))) {
                    file_put_contents(
                        base_path('.eslintrc.js'),
                        file_get_contents(__DIR__ . "/stubs/vue/.eslintrc.js")
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
        $directories = [
            "resources/views/layouts",
            "resources/js/api",
            "resources/js/exceptions",
            "resources/js/plugins",
            "resources/js/components",
            "resources/js/utils",
            "resources/js/pages",
            "resources/js/pages/auth",
            "resources/js/lang",
            "resources/js/lang/en",
            "resources/js/lang/he",
            "resources/js/router",
            "resources/js/store",
            "resources/js/store/modules",
            "resources/js/store/modules/auth",
            "resources/js/mixins",
        ];

        foreach ($directories as $directory) {
            if (!is_dir(base_path($directory))) {
                mkdir(base_path($directory), 0755, true);
            }
        }
    }

    /**
     * Create the Vue files.
     *
     * @return void
     */
    protected function createVueFiles()
    {
        $files = [
            '/api/BaseProxy.js',
            '/api/Auth.js',
            '/components/.gitkeep',
            '/components/Header.vue',
            '/utils/.gitkeep',
            '/exceptions/ApiError.js',
            '/pages/NotFound.vue',
            '/pages/Home.vue',
            '/pages/App.vue',
            '/pages/auth/Login.vue',
            '/pages/auth/Register.vue',
            '/pages/auth/PasswordEmail.vue',
            '/pages/auth/ResetPassword.vue',
            '/pages/auth/Profile.vue',
            '/lang/en/en.js',
            '/lang/en/validator.js',
            '/lang/he/he.js',
            '/lang/he/validator.js',
            '/router/index.js',
            '/store/index.js',
            '/store/modules/auth/index.js',
            '/store/modules/auth/actions.js',
            '/store/modules/auth/getters.js',
            '/store/modules/auth/mutations.js',
            '/store/modules/auth/state.js',
            '/store/modules/auth/mutation-types.js',
            '/mixins/auth.js',
            '/plugins/lang.js',
            '/plugins/meta.js',
            '/plugins/toastr.js',
            '/plugins/validator.js',
        ];

        foreach ($files as $file) {
            if (!file_exists(base_path('resources/js' . $file))) {
                file_put_contents(
                    base_path('resources/js' . $file),
                    file_get_contents(__DIR__ . '/stubs/vue' . $file)
                );
            }
        }
    }

    protected function compileControllerStub()
    {
        return str_replace(
            "{{namespace}}",
            $this->getAppNamespace(),
            file_get_contents(__DIR__ . '/stubs/Controllers/AuthController.stub')
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
