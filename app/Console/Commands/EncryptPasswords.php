<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Usuarios;
use Illuminate\Support\Facades\Hash;

class EncryptPasswords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'encrypt:passwords';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Encrypt existing plain text passwords using Bcrypt';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $usuarios = Usuarios::all();

        foreach ($usuarios as $usuario) {
            // Verificar si la contraseña necesita ser rehash
            if (Hash::needsRehash($usuario->contraseña)) {
                $this->info('Encrypting password for user: ' . $usuario->nombre);
                $usuario->contraseña = Hash::make($usuario->contraseña);
                $usuario->save();
            } else {
                $this->info('Password for user ' . $usuario->nombre . ' is already encrypted.');
            }
        }

        $this->info('Passwords encrypted successfully.');
        return 0;
    }
}
