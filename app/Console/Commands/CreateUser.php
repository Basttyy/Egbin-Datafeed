<?php

namespace App\Console\Commands;

use App\User;
use Exception;
use Illuminate\Console\Command;
use LdapRecord\Auth\BindException;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:ldapuser';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a user in the ldap directory';

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
     * @return int
     */
    public function handle()
    {
        // $user = User::create([
        //     'company' => 'Egbin PLC',
        //     'givenname' => 'Olaolu',
        //     'sn' => 'Sowono',
        //     'cn' => 'olaolu.sonowo@egbin-power.com'
        // ]);
        try {
            $user = new User;

            $user->cn = 'Olaolu Sonowo';
            $user->givenname = 'Olaolu';
            $user->sn = 'Sonowo';
            $user->company = 'Egbin PLC';

            $user->save();
        } catch (BindException $e) {
            $detailedError = optional($e->getDetailedError());

            $errorCode = $detailedError->getErrorCode();
            $diagnosticMessage = $detailedError->getDiagnosticMessage();

            $message = sprintf(
                '%s. Error Code: [%s] Diagnostic Message: %s',
                $e->getMessage(),
                $errorCode,
                $diagnosticMessage ?? 'null'
            );
        } catch (Exception $e) {
            $message = sprintf(
                '%s. Error Code: [%s]',
                $e->getMessage(),
                $e->getCode()
            );
        }

        echo $message();

        echo $user->getConnectionName();
        return 0;
    }
}
