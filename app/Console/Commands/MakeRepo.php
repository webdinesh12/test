<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakeRepo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repositary {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a repositary interface and implementation file.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        if (!is_dir(app_path() . '\Repositary')) {
            mkdir(app_path() . '\Repositary');
        }

        $directoryPath = app_path() . '\Repositary\\' . $name;
        if (is_dir($directoryPath)) {
            echo 'This Directory is Already Exist.';
            return;
        }
        
        mkdir($directoryPath);
        $interfaceContent = "<?php\n\nnamespace App\Repositary\\" . $name . ";\n\ninterface  " . $name . "Repo{\n\t// Write instensable functions\n}";

        $implementContent = "<?php\n\nnamespace App\Repositary\\".$name.";\n\nclass ".$name."RepoImpl implements  ".$name."Repo{\n\t// Write implements functions\n}";

        // touch($directoryPath.'\\'.$name.'Repo.php'); //This will create the file but here I use fopen with "w" so it doesn't need that if "w" permission is not work then use this.
        // touch($directoryPath.'\\'.$name.'RepoImpl.php'); //This will create the file but here I use fopen with "w" so it doesn't need that if "w" permission is not work then use this.

        $interfaceFile = fopen($directoryPath . '\\' . $name . 'Repo.php', 'w');
        fwrite($interfaceFile, $interfaceContent);
        fclose($interfaceFile);
        $implementFile = fopen($directoryPath . '\\' . $name . 'RepoImpl.php', 'w');
        fwrite($implementFile, $implementContent);
        fclose($implementFile);

        $this->info('The repository files are created.');
        $this->info("You can find your repository files at, \n\nInterface File: {$directoryPath}\\".$name."Repo.php\nImplements File: {$directoryPath}\\".$name."RepoImpl.php\n");
        $this->comment("Next, open the AppServiceProvider located at: " . app_path('Providers/AppServiceProvider.php'));
        $this->comment("And add the following lines in the register method:");
        $this->comment("\$this->app->bind(" . $name . "Repo::class, " . $name . "RepoImpl::class);");
    }
}
