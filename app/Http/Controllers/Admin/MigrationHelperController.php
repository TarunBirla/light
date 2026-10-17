<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use App\Models\AuctionStep;
use App\Models\AuctionFaq;

class MigrationHelperController extends Controller
{
    /**
     * Master Database & Cache Sync Route
     * Safe: NEVER drops or deletes existing data.
     * Idempotent: Checks if table exists -> if yes, only adds missing columns.
     * If table doesn't exist -> creates it.
     * Automatically clears route/config/view cache at the end!
     */
    public function syncDatabase()
    {
        $messages = [];
        $tablesChecked = 0;

        try {
            // 1. Users Table Columns
            if (Schema::hasTable('users')) {
                $tablesChecked++;
                Schema::table('users', function (Blueprint $table) use (&$messages) {
                    $columns = [
                        'company_name'   => fn() => $table->string('company_name')->nullable(),
                        'job_title'      => fn() => $table->string('job_title')->nullable(),
                        'address_line1'  => fn() => $table->string('address_line1')->nullable(),
                        'address_line2'  => fn() => $table->string('address_line2')->nullable(),
                        'city'           => fn() => $table->string('city')->nullable(),
                        'county'         => fn() => $table->string('county')->nullable(),
                        'postcode'       => fn() => $table->string('postcode')->nullable(),
                        'country'        => fn() => $table->string('country')->nullable(),
                        'main_phone'     => fn() => $table->string('main_phone')->nullable(),
                        'mobile_number'  => fn() => $table->string('mobile_number')->nullable(),
                        'is_admin'       => fn() => $table->string('is_admin')->default('0'),
                        'status'         => fn() => $table->string('status')->default('active'),
                    ];

                    foreach ($columns as $col => $definition) {
                        if (!Schema::hasColumn('users', $col)) {
                            $definition();
                            $messages[] = "Added '{$col}' to users table.";
                        }
                    }
                });
            }

            // 2. Items Table Columns
            if (Schema::hasTable('items')) {
                $tablesChecked++;
                Schema::table('items', function (Blueprint $table) use (&$messages) {
                    if (!Schema::hasColumn('items', 'product_type')) {
                        $table->string('product_type')->default('rental');
                        $messages[] = "Added 'product_type' to items table.";
                    }
                    if (!Schema::hasColumn('items', 'selling_price')) {
                        $table->decimal('selling_price', 10, 2)->nullable();
                        $messages[] = "Added 'selling_price' to items table.";
                    }
                    if (!Schema::hasColumn('items', 'rental_price')) {
                        $table->decimal('rental_price', 10, 2)->nullable();
                        $messages[] = "Added 'rental_price' to items table.";
                    }
                    if (!Schema::hasColumn('items', 'condition')) {
                        $table->string('condition')->nullable();
                        $messages[] = "Added 'condition' to items table.";
                    }
                });
            }

            // 3. Equipment Requests Table
            if (!Schema::hasTable('equipment_requests')) {
                Schema::create('equipment_requests', function (Blueprint $table) {
                    $table->id();
                    $table->unsignedBigInteger('user_id')->nullable();
                    $table->string('gaffer')->nullable();
                    $table->string('email')->nullable();
                    $table->string('contact')->nullable();
                    $table->string('production_company')->nullable();
                    $table->string('production_title')->nullable();
                    $table->string('production_contact')->nullable();
                    $table->string('dop')->nullable();
                    $table->date('rig_from')->nullable();
                    $table->date('rig_to')->nullable();
                    $table->date('prelight_from')->nullable();
                    $table->date('prelight_to')->nullable();
                    $table->date('shoot_from')->nullable();
                    $table->date('shoot_to')->nullable();
                    $table->date('derig_from')->nullable();
                    $table->date('derig_to')->nullable();
                    $table->string('address_line_1')->nullable();
                    $table->string('address_line_2')->nullable();
                    $table->string('address_line_3_postcode')->nullable();
                    $table->text('location_address')->nullable();
                    $table->string('status')->default('submitted');
                    $table->timestamps();
                });
                $messages[] = "Created 'equipment_requests' table.";
            } else {
                $tablesChecked++;
            }

            // 4. Equipment Request Items Table
            if (!Schema::hasTable('equipment_request_items')) {
                Schema::create('equipment_request_items', function (Blueprint $table) {
                    $table->id();
                    $table->unsignedBigInteger('equipment_request_id');
                    $table->unsignedBigInteger('category_id')->nullable();
                    $table->unsignedBigInteger('product_id')->nullable();
                    $table->integer('quantity')->default(1);
                    $table->timestamps();
                });
                $messages[] = "Created 'equipment_request_items' table.";
            } else {
                $tablesChecked++;
            }

            // 5. Auction Products Table
            if (!Schema::hasTable('auction_products')) {
                Schema::create('auction_products', function (Blueprint $table) {
                    $table->id();
                    $table->unsignedBigInteger('category_id')->nullable();
                    $table->string('title');
                    $table->text('description')->nullable();
                    $table->json('image')->nullable();
                    $table->integer('qty')->default(1);
                    $table->decimal('minprice', 12, 2)->default(0.00);
                    $table->string('status')->default('active');
                    $table->integer('sort_order')->default(0);
                    $table->string('shipping_type')->default('collection');
                    $table->timestamps();
                });
                $messages[] = "Created 'auction_products' table.";
            } else {
                $tablesChecked++;
                if (!Schema::hasColumn('auction_products', 'shipping_type')) {
                    Schema::table('auction_products', function (Blueprint $table) use (&$messages) {
                        $table->string('shipping_type')->default('collection');
                        $messages[] = "Added 'shipping_type' to auction_products table.";
                    });
                }
            }

            // 6. Auction Requests Table
            if (!Schema::hasTable('auction_requests')) {
                Schema::create('auction_requests', function (Blueprint $table) {
                    $table->id();
                    $table->unsignedBigInteger('auction_product_id');
                    $table->string('name');
                    $table->string('email');
                    $table->string('phone');
                    $table->integer('qty')->default(1);
                    $table->text('message')->nullable();
                    $table->decimal('bid_price', 12, 2)->nullable();
                    $table->text('address')->nullable();
                    $table->string('status')->default('pending');
                    $table->timestamps();
                });
                $messages[] = "Created 'auction_requests' table.";
            } else {
                $tablesChecked++;
                Schema::table('auction_requests', function (Blueprint $table) use (&$messages) {
                    if (!Schema::hasColumn('auction_requests', 'bid_price')) {
                        $table->decimal('bid_price', 12, 2)->nullable();
                        $messages[] = "Added 'bid_price' to auction_requests table.";
                    }
                    if (!Schema::hasColumn('auction_requests', 'address')) {
                        $table->text('address')->nullable();
                        $messages[] = "Added 'address' to auction_requests table.";
                    }
                });
            }

            // 7. Auction Steps Table
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
            } else {
                $tablesChecked++;
            }

            // 8. Auction FAQs Table
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
            } else {
                $tablesChecked++;
            }

            // 9. Visitors Table for Analytics
            if (!Schema::hasTable('visitors')) {
                Schema::create('visitors', function (Blueprint $table) {
                    $table->id();
                    $table->string('ip_address')->unique();
                    $table->string('country')->nullable();
                    $table->string('country_code', 10)->nullable();
                    $table->string('city')->nullable();
                    $table->string('region')->nullable();
                    $table->decimal('latitude', 10, 7)->nullable();
                    $table->decimal('longitude', 10, 7)->nullable();
                    $table->string('isp')->nullable();
                    $table->string('browser')->nullable();
                    $table->string('browser_version')->nullable();
                    $table->string('device')->default('Desktop');
                    $table->string('os')->nullable();
                    $table->text('landing_page')->nullable();
                    $table->text('last_page')->nullable();
                    $table->text('referrer')->nullable();
                    $table->integer('total_visits')->default(1);
                    $table->timestamp('last_visit_at')->nullable();
                    $table->timestamps();
                });
                $messages[] = "Created 'visitors' analytics table.";
            } else {
                $tablesChecked++;
                Schema::table('visitors', function (Blueprint $table) use (&$messages) {
                    $visitorCols = [
                        'country_code'    => fn() => $table->string('country_code', 10)->nullable(),
                        'region'          => fn() => $table->string('region')->nullable(),
                        'latitude'        => fn() => $table->decimal('latitude', 10, 7)->nullable(),
                        'longitude'       => fn() => $table->decimal('longitude', 10, 7)->nullable(),
                        'isp'             => fn() => $table->string('isp')->nullable(),
                        'browser_version' => fn() => $table->string('browser_version')->nullable(),
                        'landing_page'    => fn() => $table->text('landing_page')->nullable(),
                        'last_page'       => fn() => $table->text('last_page')->nullable(),
                        'total_visits'    => fn() => $table->integer('total_visits')->default(1),
                        'last_visit_at'   => fn() => $table->timestamp('last_visit_at')->nullable(),
                    ];
                    foreach ($visitorCols as $vCol => $vDef) {
                        if (!Schema::hasColumn('visitors', $vCol)) {
                            $vDef();
                            $messages[] = "Added '{$vCol}' to visitors table.";
                        }
                    }
                });
            }

            // 10. Page Views Table for Analytics Journey
            if (!Schema::hasTable('page_views')) {
                Schema::create('page_views', function (Blueprint $table) {
                    $table->id();
                    $table->unsignedBigInteger('visitor_id')->nullable();
                    $table->string('ip_address')->nullable();
                    $table->text('url');
                    $table->string('page_name')->nullable();
                    $table->text('referrer')->nullable();
                    $table->string('method', 10)->default('GET');
                    $table->timestamp('viewed_at')->useCurrent();
                    $table->timestamps();

                    $table->index('visitor_id');
                    $table->index('ip_address');
                    $table->index('viewed_at');
                });
                $messages[] = "Created 'page_views' analytics table.";
            } else {
                $tablesChecked++;
            }

            // 11. Account Applications Table for Trade Accounts
            if (!Schema::hasTable('account_applications')) {
                Schema::create('account_applications', function (Blueprint $table) {
                    $table->id();
                    $table->string('contact_person_name');
                    $table->string('full_legal_name');
                    $table->string('trading_name')->nullable();
                    $table->string('business_type')->nullable();
                    $table->string('company_registration_no')->nullable();
                    $table->string('vat_number')->nullable();
                    $table->text('registered_address');
                    $table->text('trading_address')->nullable();
                    $table->string('telephone');
                    $table->string('email');
                    $table->string('website')->nullable();
                    $table->string('status')->default('pending');
                    $table->text('admin_notes')->nullable();
                    $table->timestamps();
                });
                $messages[] = "Created 'account_applications' table.";
            } else {
                $tablesChecked++;
                Schema::table('account_applications', function (Blueprint $table) use (&$messages) {
                    if (!Schema::hasColumn('account_applications', 'admin_notes')) {
                        $table->text('admin_notes')->nullable();
                        $messages[] = "Added 'admin_notes' to account_applications table.";
                    }
                });
            }

            // 12. Blogs Table
            if (!Schema::hasTable('blogs')) {
                Schema::create('blogs', function (Blueprint $table) {
                    $table->id();
                    $table->string('title');
                    $table->string('slug')->unique();
                    $table->string('image')->nullable();
                    $table->text('short_description')->nullable();
                    $table->longText('description')->nullable();
                    $table->string('author')->default('Light As Air Team');
                    $table->string('status')->default('active');
                    $table->unsignedBigInteger('views')->default(0);
                    $table->timestamps();
                });
                $messages[] = "Created 'blogs' table.";
            } else {
                $tablesChecked++;
                Schema::table('blogs', function (Blueprint $table) use (&$messages) {
                    if (!Schema::hasColumn('blogs', 'views')) {
                        $table->unsignedBigInteger('views')->default(0);
                        $messages[] = "Added 'views' to blogs table.";
                    }
                    if (!Schema::hasColumn('blogs', 'short_description')) {
                        $table->text('short_description')->nullable();
                        $messages[] = "Added 'short_description' to blogs table.";
                    }
                });
            }

            // 13. Password Reset Tokens Table (if missing)
            if (!Schema::hasTable('password_reset_tokens') && !Schema::hasTable('password_resets')) {
                Schema::create('password_reset_tokens', function (Blueprint $table) {
                    $table->string('email')->primary();
                    $table->string('token');
                    $table->timestamp('created_at')->nullable();
                });
                $messages[] = "Created 'password_reset_tokens' table.";
            }

            // 14. Seed default Auction Steps if empty
            if (Schema::hasTable('auction_steps') && AuctionStep::count() === 0) {
                $defaultSteps = [
                    ['step_number' => 1, 'title' => 'Register your interest', 'description' => "Create an account using the registration link. It's free and takes less than two minutes.", 'sort_order' => 1, 'status' => 'active'],
                    ['step_number' => 2, 'title' => 'Receive confirmation', 'description' => "You'll receive an email confirming your registration with all the details you need.", 'sort_order' => 2, 'status' => 'active'],
                    ['step_number' => 3, 'title' => 'View auction lots', 'description' => "Browse all lots in advance so you can plan which items you want to bid on.", 'sort_order' => 3, 'status' => 'active'],
                    ['step_number' => 4, 'title' => 'Place your bids', 'description' => "Once bidding opens, log in and place your bids. If a bid is placed within the last 2 minutes of an auction ending, the closing time is automatically extended by 2 minutes.", 'sort_order' => 4, 'status' => 'active'],
                    ['step_number' => 5, 'title' => 'Viewing day (optional)', 'description' => "A viewing day (by appointment only) will be held 10:00–15:00 in Teddington, London so you can inspect items in person.", 'sort_order' => 5, 'status' => 'active'],
                    ['step_number' => 6, 'title' => 'Winning an item', 'description' => "When the auction ends, if your bid is highest and the reserve is met, our team will contact you.", 'sort_order' => 6, 'status' => 'active'],
                    ['step_number' => 7, 'title' => 'Payment', 'description' => "You'll receive an invoice after the auction closes. Payment must be made in full within seven days via bank transfer.", 'sort_order' => 7, 'status' => 'active'],
                    ['step_number' => 8, 'title' => 'Collection', 'description' => "Once payment is confirmed, collect your items from Visual Impact UK, Unit 4 Teddington Business Park, Station Road, Teddington TW11 9BQ (Mon–Fri, 09:00–17:00).", 'sort_order' => 8, 'status' => 'active'],
                ];
                foreach ($defaultSteps as $step) {
                    AuctionStep::create($step);
                }
                $messages[] = "Seeded 8 default Auction Steps.";
            }

            // 15. Seed default FAQs if empty
            if (Schema::hasTable('auction_faqs') && AuctionFaq::count() === 0) {
                $defaultFaqs = [
                    ['question' => 'How can I bid on lots included in the auction?', 'answer' => 'Simply register or log in to your account, select the auction product you are interested in, enter your bid offer price and quantity, and submit your bid request.', 'sort_order' => 1, 'status' => 'active'],
                    ['question' => 'When does the auction start and when can I start bidding?', 'answer' => 'Bidding opens as soon as an auction lot is marked active on the website. You can place your bids anytime before the auction closing date.', 'sort_order' => 2, 'status' => 'active'],
                    ['question' => 'When does the auction end?', 'answer' => 'Auction end dates and times are specified on each lot. Bids placed within the final 2 minutes will automatically extend the closing time by 2 minutes.', 'sort_order' => 3, 'status' => 'active'],
                    ['question' => 'Can I view the lots before bidding?', 'answer' => 'Yes, viewing days are available by appointment only between 10:00–15:00 at our Teddington location.', 'sort_order' => 4, 'status' => 'active'],
                    ['question' => 'How and when do I pay for auctions I\'ve won?', 'answer' => 'Winning bidders will receive an official invoice upon auction close. Full payment is required within 7 days via bank transfer.', 'sort_order' => 5, 'status' => 'active'],
                    ['question' => 'Are there any fees such as hammer or bidding fees?', 'answer' => 'All applicable fees or shipping costs are clearly stated on each auction product listing under the shipping & pricing details.', 'sort_order' => 6, 'status' => 'active'],
                    ['question' => 'When and where do I collect the goods?', 'answer' => 'Once payment is confirmed, items can be collected from Visual Impact UK, Unit 4 Teddington Business Park, Station Road, Teddington TW11 9BQ (Mon–Fri, 09:00–17:00).', 'sort_order' => 7, 'status' => 'active'],
                ];
                foreach ($defaultFaqs as $faq) {
                    AuctionFaq::create($faq);
                }
                $messages[] = "Seeded 7 default Auction FAQs.";
            }

            // 16. Automatically Clear All Caches!
            $cacheCleared = false;
            try {
                Artisan::call('optimize:clear');
                $cacheCleared = true;
                $messages[] = "Cleared application route, config, view, and data cache.";
            } catch (\Exception $ce) {
                // In case Artisan cannot run on shared host
                $messages[] = "Cache clear notice: " . $ce->getMessage();
            }

            return response()->json([
                'status'         => true,
                'message'        => 'Live Database and Application Cache Successfully Synchronized! ZERO data was modified or lost.',
                'tables_checked' => $tablesChecked,
                'cache_cleared'  => $cacheCleared,
                'details'        => empty($messages) ? ['All tables and parameters are already up to date!'] : $messages,
            ], 200, [], JSON_PRETTY_PRINT);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Error during database sync: ' . $e->getMessage(),
                'line'    => $e->getLine(),
                'file'    => basename($e->getFile()),
            ], 500, [], JSON_PRETTY_PRINT);
        }
    }

    /**
     * Backwards-compatible alias for existing migration links
     */
    public function runMigrations()
    {
        return $this->syncDatabase();
    }

    /**
     * Clear all caches on live server via simple URL hit
     */
    public function clearCache()
    {
        try {
            Artisan::call('optimize:clear');
            return response()->json([
                'status'  => true,
                'message' => 'All caches cleared successfully! (Route, config, cache, views cleared)',
                'output'  => Artisan::output(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Error clearing cache: ' . $e->getMessage(),
            ], 500);
        }
    }
}
