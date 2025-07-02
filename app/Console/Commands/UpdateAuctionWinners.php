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
                $duration = (int) $auction->duration;

                if ($duration <= 0) {
                    $this->warn("Invalid duration for auction ID: {$auction->id}");
                    continue;
                }

                $expirationDate = $auction->updated_at->copy()->addDays($duration);

                if (now()->greaterThanOrEqualTo($expirationDate)) {
                    $topContributor = Contributor::with(['user', 'auction'])
                        ->where('auction_id', $auction->id)
                        ->orderByDesc('bit_online')
                        ->first();

                    if ($topContributor) {
                        $topContributor->status = 'winner';
                        $topContributor->save();

                        $auction->status = 'Completed';
                        $auction->save();

                        $link = 'http://137.59.180.219:8000/winner-payment?' . http_build_query([
                            'contributor_id' => $topContributor->id,
                            'amount'         => $topContributor->bit_online,
                            'auction_name'   => $auction->title,
                            'description'    => $auction->description,
                            'winner_name'    => $topContributor->user->full_name,
                            'email'          => $topContributor->user->email,
                        ]);

                        $data = [
                            'title'          => $auction->title,
                            'description'    => $auction->description,
                            'name'           => $topContributor->user->full_name,
                            'email'          => $topContributor->user->email,
                            'contact_number' => $topContributor->contact_number,
                            'amount'         => $topContributor->bit_online,
                            'date'           => $topContributor->created_at,
                            'status'         => $topContributor->status,
                            'link'           => $link,
                        ];

                        try {
                            Mail::to($data['email'])->queue(new ContributorOfAuctionWinnerMail($data));
                            $this->info("Winner selected and notified for auction ID: {$auction->id}");
                        } catch (\Exception $e) {
                            Log::error("Email send failed for auction ID: {$auction->id}. Error: " . $e->getMessage());
                        }
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
