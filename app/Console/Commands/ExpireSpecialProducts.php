<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

class ExpireSpecialProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:expire-sales';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire finished special sale products';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expired = Product::whereNotNull('sale_price')->whereNotNull('sale_end_at')->where('sale_end_at', '<=', now())->update([
            'sale_price' => null,
            'sale_start_at' => null,
            'sale_end_at' => null,
            'is_on_special_sale' => 0,
            'updated_at' => now(),
        ]);
        $this->info("Expired {$expired} products successfully.");
    }
}
