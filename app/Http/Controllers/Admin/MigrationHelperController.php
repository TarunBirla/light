<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use App\Models\AuctionStep;
use App\Models\AuctionFaq;

class MigrationHelperController extends Controller
{
    public function runMigrations()
    {
        $messages = [];

        // 1. Users Table Columns
        Schema::table('users', function (Blueprint $table) use (&$messages) {
            if (!Schema::hasColumn('users', 'company_name')) {
                $table->string('company_name')->nullable();
                $messages[] = "Added 'company_name' to users table.";
            }
            if (!Schema::hasColumn('users', 'job_title')) {
                $table->string('job_title')->nullable();
                $messages[] = "Added 'job_title' to users table.";
            }
            if (!Schema::hasColumn('users', 'address_line1')) {
                $table->string('address_line1')->nullable();
                $messages[] = "Added 'address_line1' to users table.";
            }
            if (!Schema::hasColumn('users', 'address_line2')) {
                $table->string('address_line2')->nullable();
                $messages[] = "Added 'address_line2' to users table.";
            }
            if (!Schema::hasColumn('users', 'city')) {
                $table->string('city')->nullable();
                $messages[] = "Added 'city' to users table.";
            }
            if (!Schema::hasColumn('users', 'county')) {
                $table->string('county')->nullable();
                $messages[] = "Added 'county' to users table.";
            }
            if (!Schema::hasColumn('users', 'postcode')) {
                $table->string('postcode')->nullable();
                $messages[] = "Added 'postcode' to users table.";
            }
            if (!Schema::hasColumn('users', 'country')) {
                $table->string('country')->nullable();
                $messages[] = "Added 'country' to users table.";
            }
            if (!Schema::hasColumn('users', 'main_phone')) {
                $table->string('main_phone')->nullable();
                $messages[] = "Added 'main_phone' to users table.";
            }
            if (!Schema::hasColumn('users', 'mobile_number')) {
                $table->string('mobile_number')->nullable();
                $messages[] = "Added 'mobile_number' to users table.";
            }
        });

        // 2. Create auction_steps Table
        if (!Schema::hasTable('auction_steps')) {
            Schema::create('auction_steps', function (Blueprint $table) {
                $table->id();
                $table->integer('step_number')->default(1);
                $table->string('title');
                $table->text('description')->nullable();
                $table->integer('sort_order')->default(0);
                $table->string('status')->default('active');
                $table->timestamps();
            });
            $messages[] = "Created 'auction_steps' table.";
        }

        // 3. Create auction_faqs Table
        if (!Schema::hasTable('auction_faqs')) {
            Schema::create('auction_faqs', function (Blueprint $table) {
                $table->id();
                $table->text('question');
                $table->text('answer')->nullable();
                $table->integer('sort_order')->default(0);
                $table->string('status')->default('active');
                $table->timestamps();
            });
            $messages[] = "Created 'auction_faqs' table.";
        }

        // 4. Seed default Auction Steps if empty
        if (AuctionStep::count() === 0) {
            $defaultSteps = [
                [
                    'step_number' => 1,
                    'title'       => 'Register your interest',
                    'description' => "Create an account using the registration link. It's free and takes less than two minutes.",
                    'sort_order'  => 1,
                    'status'      => 'active',
                ],
                [
                    'step_number' => 2,
                    'title'       => 'Receive confirmation',
                    'description' => "You'll receive an email confirming your registration with all the details you need.",
                    'sort_order'  => 2,
                    'status'      => 'active',
                ],
                [
                    'step_number' => 3,
                    'title'       => 'View auction lots',
                    'description' => "Browse all lots in advance so you can plan which items you want to bid on.",
                    'sort_order'  => 3,
                    'status'      => 'active',
                ],
                [
                    'step_number' => 4,
                    'title'       => 'Place your bids',
                    'description' => "Once bidding opens, log in and place your bids. If a bid is placed within the last 2 minutes of an auction ending, the closing time is automatically extended by 2 minutes.",
                    'sort_order'  => 4,
                    'status'      => 'active',
                ],
                [
                    'step_number' => 5,
                    'title'       => 'Viewing day (optional)',
                    'description' => "A viewing day (by appointment only) will be held 10:00–15:00 in Teddington, London so you can inspect items in person.",
                    'sort_order'  => 5,
                    'status'      => 'active',
                ],
                [
                    'step_number' => 6,
                    'title'       => 'Winning an item',
                    'description' => "When the auction ends, if your bid is highest and the reserve is met, our team will contact you.",
                    'sort_order'  => 6,
                    'status'      => 'active',
                ],
                [
                    'step_number' => 7,
                    'title'       => 'Payment',
                    'description' => "You'll receive an invoice after the auction closes. Payment must be made in full within seven days via bank transfer.",
                    'sort_order'  => 7,
                    'status'      => 'active',
                ],
                [
                    'step_number' => 8,
                    'title'       => 'Collection',
                    'description' => "Once payment is confirmed, collect your items from Visual Impact UK, Unit 4 Teddington Business Park, Station Road, Teddington TW11 9BQ (Mon–Fri, 09:00–17:00).",
                    'sort_order'  => 8,
                    'status'      => 'active',
                ],
            ];

            foreach ($defaultSteps as $step) {
                AuctionStep::create($step);
            }
            $messages[] = "Seeded 8 default Auction Steps.";
        }

        // 5. Seed default FAQs if empty
        if (AuctionFaq::count() === 0) {
            $defaultFaqs = [
                [
                    'question'   => 'How can I bid on lots included in the auction?',
                    'answer'     => 'Simply register or log in to your account, select the auction product you are interested in, enter your bid offer price and quantity, and submit your bid request.',
                    'sort_order' => 1,
                    'status'     => 'active',
                ],
                [
                    'question'   => 'When does the auction start and when can I start bidding?',
                    'answer'     => 'Bidding opens as soon as an auction lot is marked active on the website. You can place your bids anytime before the auction closing date.',
                    'sort_order' => 2,
                    'status'     => 'active',
                ],
                [
                    'question'   => 'When does the auction end?',
                    'answer'     => 'Auction end dates and times are specified on each lot. Bids placed within the final 2 minutes will automatically extend the closing time by 2 minutes.',
                    'sort_order' => 3,
                    'status'     => 'active',
                ],
                [
                    'question'   => 'Can I view the lots before bidding?',
                    'answer'     => 'Yes, viewing days are available by appointment only between 10:00–15:00 at our Teddington location.',
                    'sort_order' => 4,
                    'status'     => 'active',
                ],
                [
                    'question'   => 'How and when do I pay for auctions I\'ve won?',
                    'answer'     => 'Winning bidders will receive an official invoice upon auction close. Full payment is required within 7 days via bank transfer.',
                    'sort_order' => 5,
                    'status'     => 'active',
                ],
                [
                    'question'   => 'Are there any fees such as hammer or bidding fees?',
                    'answer'     => 'All applicable fees or shipping costs are clearly stated on each auction product listing under the shipping & pricing details.',
                    'sort_order' => 6,
                    'status'     => 'active',
                ],
                [
                    'question'   => 'When and where do I collect the goods?',
                    'answer'     => 'Once payment is confirmed, items can be collected from Visual Impact UK, Unit 4 Teddington Business Park, Station Road, Teddington TW11 9BQ (Mon–Fri, 09:00–17:00).',
                    'sort_order' => 7,
                    'status'     => 'active',
                ],
            ];

            foreach ($defaultFaqs as $faq) {
                AuctionFaq::create($faq);
            }
            $messages[] = "Seeded 7 default Auction FAQs.";
        }

        return response()->json([
            'status'   => true,
            'message'  => 'Live database tables and parameters updated successfully with ZERO data loss!',
            'details'  => $messages,
        ]);
    }
}
