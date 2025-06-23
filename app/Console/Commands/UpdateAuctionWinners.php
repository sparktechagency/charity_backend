<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Auction;
use App\Models\Contributor;
use App\Mail\ContributorOfAuctionWinnerMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class UpdateAuctionWinners extends Command
{
    protected $signature = 'auction:winner';
    protected $description = 'Automatically select auction winners after auction duration ends';

    public function handle()
    {
        $this->info('Starting to check expired auctions...');

        Auction::where('status', 'Declared')->chunk(100, function ($auctions) {
            foreach ($auctions as $auction) {
                // Validate duration
                if (!is_numeric($auction->duration)) {
                    $this->warn("Invalid duration for auction ID: {$auction->id}");
                    continue;
                }

                $expirationDate = $auction->updated_at->copy()->addDays($auction->duration);

                if (now()->greaterThanOrEqualTo($expirationDate)) {
                    $topContributor = Contributor::with(['user', 'auction'])->where('auction_id', $auction->id)
                        ->orderByDesc('bit_online')
                        ->first();

                    if ($topContributor) {
                        $topContributor->status = 'winner';
                        $topContributor->save();

                        $auction->status = 'Completed';
                        $auction->save();

                        // Build the external payment link
                        $link = 'https://virtuehope.com/winner-payment?' . http_build_query([
                            'contributor_id' => $topContributor->id,
                            'amount'         => $topContributor->bit_online,
                            'auction_name'   => $auction->title,
                            'description'    => $auction->description,
                            'winner_name'    => $topContributor->user->name,
                            'email'          => $topContributor->email,
                        ]);

                        try {
                            Mail::to($topContributor->email)->queue(
                                new ContributorOfAuctionWinnerMail($topContributor, $link)
                            );
                        } catch (\Exception $e) {
                            Log::error("Failed to send winner email for auction ID {$auction->id}: " . $e->getMessage());
                        }

                        $this->info("Winner selected and notified for auction ID: {$auction->id}");
                    } else {
                        $this->warn("No contributors found for auction ID: {$auction->id}");
                        $auction->status = 'Completed';
                        $auction->save();
                    }
                }
            }
        });

        $this->info('Auction winner selection process completed.');
        return 0;
    }
}
