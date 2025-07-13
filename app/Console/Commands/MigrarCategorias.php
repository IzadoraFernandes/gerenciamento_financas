<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MigrarCategorias extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:migrar-categorias';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $categorias = \DB::table('transacaos')
            ->select('categoria')
            ->whereNotNull('categoria')
            ->distinct()
            ->pluck('categoria');

        foreach ($categorias as $nome) {
            \App\Models\Categoria::firstOrCreate(['nome' => $nome]);
        }

        foreach (\App\Models\Transacao::all() as $transacao) {
            if ($transacao->categoria) {
                $categoria = \App\Models\Categoria::where('nome', $transacao->categoria)->first();
                $transacao->id_categoria = $categoria->id;
                $transacao->save();
            }
        }

        $this->info('Categorias migradas com sucesso.');
    }

}
