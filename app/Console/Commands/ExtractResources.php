<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ExtractResources extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'extract:resources';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Extract resources';

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
     * @return mixed
     */
    public function handle()
    {
        $resources = file_get_contents(__DIR__ . "/resources.txt");
        $lines = explode("\n", $resources);

        $extracted = [];
        $startLink = false;
        $linkIndex = 0;
        $currentLink = [
            'title' => '',
            'url' => '',
            'desc' => ''
        ];
        $currentCategory = '';

        foreach ($lines as $line) {
            if (stristr($line, "##") != false ) {
                $startLink = false;
                $currentCategory = str_replace("#","", $line);
                continue;
            }

            if (empty(trim($line))) {
                $startLink = true;
                continue;
            }

            if ($startLink && $linkIndex == 0) {
                $currentLink['title'] = $line;
                $linkIndex++;
                continue;
            }

            if ( $startLink && $linkIndex == 1 ) {
                $currentLink['desc'] = $line;
                $linkIndex++;
                continue;
            }

            if ( $startLink && $linkIndex == 2 ) {
                $currentLink['url'] = $line;
                if (!empty($currentCategory)) {
                    $extracted[$currentCategory][] = $currentLink;
                    $currentLink = [
                        'title' => '',
                        'url'   => '',
                        'desc'  => ''
                    ];
                }

                $linkIndex = 0;
                continue;
            }

        }

        $output = '';
        foreach ($extracted as $category => $links) {
$output .= '<div class="row">
                        <div class="col-md-8 col-md-offset-2">
                            <h4 class="text-white">'.$category.'</h4>
                        </div>

                        <div class="clearfix"></div>

                        <div class="col-md-8 col-md-offset-2">';

            foreach ($links as $link) {
                $output .= '<!-- Question/Answer -->
                            <div>
                                <h4 class="question">
                                    <a href="' . $link['url'] .'"
                                       class="text-custom"
                                       target="_blank">
                                        ' . $link['title'] . '
                                    </a>
                                </h4>
                                <p class="answer text-muted">
                                    ' . $link['desc'] . '
                                </p>
                            </div>
';
            }

            $output .= '</div>
                        <!--/col-md-5 -->

                        <div class="col-md-5">

                        </div>
                        <!--/col-md-5-->
                    </div>';
        }

        file_put_contents('resources.html', $output);

    }
}
