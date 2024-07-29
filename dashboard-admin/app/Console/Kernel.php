<?php namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
//        // Atualiza os valores dos servicos
//        $schedule->command('canal-venda:dados')->dailyAt('1:00')->withoutOverlapping();
//
//        // Ranking dos servicos
//        $schedule->command('servicos:ranking')->weeklyOn(1, '2:00')->withoutOverlapping(); // Segunda feira
//
//        // Verifica a disponibilidade dos servicos
//        $schedule->command('servicos:disponibilidade')->weeklyOn(2,'5:00')->withoutOverlapping(); // Terca
//        $schedule->command('servicos:disponibilidade')->weeklyOn(4,'5:00')->withoutOverlapping(); // Quinta
//        $schedule->command('servicos:disponibilidade')->weeklyOn(6,'5:00')->withoutOverlapping(); // Sabado
//
//        // Atualiza as secoes da home destino
//        $schedule->command('servicos:home tipo=vendidos')->weeklyOn(1,'4:00')->withoutOverlapping(); // Segunda feira
//        $schedule->command('servicos:home')->everyTenMinutes()->withoutOverlapping(); // A cada 10 minutos
//
//        // Limpa o cache dos canais de venda
//        $schedule->command('canal-venda:reset-cache')->dailyAt('1:05')->withoutOverlapping();
//        $schedule->command('canal-venda:reset-cache')->dailyAt('13:05')->withoutOverlapping();
//
//        // Backup do banco de dados
        $schedule->command('backup:run --only-db')->dailyAt('00:00')->withoutOverlapping();
        $schedule->command('backup:run --only-db')->dailyAt('06:00')->withoutOverlapping();
        $schedule->command('backup:run --only-db')->dailyAt('12:00')->withoutOverlapping();
        $schedule->command('backup:run --only-db')->dailyAt('18:00')->withoutOverlapping();
        

        // Atualizar a situação das reservas por PIX
        $schedule->command('pix:atualizar')->everyMinute()->withoutOverlapping();


        // Geração de faturas mensais e da primeira quinzena
        $schedule->command('fatura:gerar')->monthlyOn(1, '01:00')->withoutOverlapping();

        // Geração de faturas da segunda quinzena
        $schedule->command('fatura:gerar')->monthlyOn(16, '01:00')->withoutOverlapping();

        // Geração de faturas semanais
        $schedule->command('fatura:gerar')->weeklyOn(1, '01:00')->withoutOverlapping();



        // $schedule->command('test:crontab')->everyMinute();

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        //require base_path('routes/console.php');
    }
}
