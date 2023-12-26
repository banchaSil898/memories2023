<?php

function load_memories_css()
{
?>
    <link href="https://fonts.googleapis.com/css?family=Kanit" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Bebas+Neue" rel="stylesheet" />

    <style>
        .td-page-content {
        padding-bottom: 0px;
    }
        #memories .mainbanner {
            width: 100%;
            height: auto;
        }
        #memories .tenpeople-area .td_mod_wrap {
            padding: 0px;
            border: 0px solid #acacac;
            background-color: rgba(0, 0, 0, 0);
        }
        #memories .tenpeople-area .td_block_text_with_title {
            margin: 0px;
        }
        #memories .tenpeople-area .td_block_text_with_title img {
            margin-bottom: 0px;
        }
        #memories .ads-banner .td_block_text_with_title .td_mod_wrap {
            background-color: #c2c2c2 !important;
            border: 0px;
        }
        .promotion-banner{
            text-align:center;
        }
        .promotion-banner img{
            max-width: 85%;
        }
        #tenpeople {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            background: rgba(0, 0, 0, 0) url('<?php echo  get_theme_file_uri(); ?>/includes/memories/images/people-background-tenpeople.png') top center no-repeat;
            background-size: contain;
            padding-top: 0px;
            padding-bottom: 0px;
        }
        #tenpeople .body {
            display: flex;
            flex-wrap: wrap;
            margin-top: 100px;
            margin-bottom: 20px;
            justify-content: center;
        }
        #tenpeople .peoplecontainer {
            margin-left: -20px;
        }
        #tenpeople .title {
            padding-top: 30px;
            text-align: center;
        }
        #tenpeople .red-header {
            color: #F00;
            text-align: center;
            font-family: Kanit;
            font-style: normal;
            font-weight: 600;
            line-height: normal;
            text-transform: uppercase;
        }
        #tenpeople .white-header {
            color: #FFF;
            font-family: Bebas Neue;
            font-style: normal;
            font-weight: 400;
            line-height: normal;
            text-transform: uppercase;
        }
        #tenpeople .column {
            box-sizing: border-box;
        }
        #tenpeople .row {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
        }
        #tenpeople img {
            display: block;
            height: 100%;
            transition: transform 0.5s;
            /* Animation */
        }
        #tenpeople img:hover {
            transform: scale(1.1);
        }
        .news-memories .td_block_text_with_title .td_mod_wrap {
            padding: 0px;
            border: 0px;
        }
        .news-memories .td_block_text_with_title {
            margin-bottom: 0px;
        }
        #monthmemories {
            background: url('<?php echo  get_theme_file_uri(); ?>/includes/memories/images/monthly_event.png') no-repeat center;
            background-size: 100% 100%;
            background-position: left top;
            padding-top: 50px;
            padding-bottom: 30px;
        }

        #monthmemories .monthly {
            display: flex;
            flex-direction: row;
            justify-content: center;
            color: aliceblue;
        }

        #monthmemories .monthly>div {
            display: flex;
        }

        #monthmemories .monthly>div.bullet {
            flex-direction: column;
        }

        #monthmemories .monthly>div.bullet article,
        #monthmemories .monthly>div.bullet footer {
            font-size: 1.1REM;
        }

        #monthmemories .bullet ul li {
            list-style-type: none;
            margin-left: 0;
        }

        #monthmemories .monthly .frame {
            position: relative;
            border: 4px solid red;
            aspect-ratio: 3/2;
            max-width: 100%;
            height:auto;
        }

        #monthmemories .monthly .frame a {
            overflow: hidden;
        }

        #monthmemories .monthly .frame a img {
            object-fit: cover;
            object-position: top center;
            aspect-ratio: 3/2;
            min-width: 100%;
            min-height: 100%;
        }

        #monthmemories a:has(footer) {
            color: white;
        }

        #monthmemories a:has(footer):hover {
            color: red;
        }

        #monthmemories .imagecrop {
            position: absolute;
            transition: all .4s;
            top: 1EM;
            right: 1EM;
            overflow: hidden;
            aspect-ratio: 3/2;
            width: 100%;
            height: 100%;
        }

        #monthmemories article {
            margin-top: 1.5REM;
        }

        #monthmemories header>div {
            margin: -0.5REM 0;
            font-family: Kanit !important;
        }

        #monthmemories header>.month {
            font: 2EM bolder;
            text-decoration: underline red 2px;
            text-underline-offset: 0.5REM;
        }

        #monthmemories header>.year {
            color: rgba(255, 255, 255, 0.00);
            -webkit-text-stroke: 1px white;
            font: 1.5EM bolder;
        }

        #monthmemories ul {
            margin-block-start: 0.5EM;
            margin-block-end: 0.5EM;
        }

        .imagecrop::after {
            display: block;
            position: absolute;
            background-image: linear-gradient(to bottom, rgba(255, 255, 255, 0) 0, #8D2215 100%);
            content: '';
        }
        #monthmemories .monthly>div.bullet article, 
        #monthmemories .monthly>div.bullet footer,
        #monthmemories .bullet ul li {
            font-size: 1.3REM;
            font-family: 'Kanit', sans-serif;
            line-height: 1.4;
        }

        @media screen and (min-width: 1281px) {
            :root {
                --img-width: 600px;
                --img-height: 400px;
            }

            #tenpeople .red-header,
            #tenpeople .white-header {
                font-size: 3.2EM;
            }

            #tenpeople .row {
                margin-right: -15px;
                margin-left: 38px;
                height: 308px;
            }

            #tenpeople .row:nth-child(1) {
                margin-left: 105px;
            }

            #tenpeople .column {
                width: 259px;
                height: 300px;
                margin: 0 -28px;
                margin-bottom: 20px;
            }

            #tenpeople .column:nth-child(even) {
                margin-top: -20px;
            }

            #tenpeople .column:nth-child(odd) {
                margin-left: -38px;
            }

            #monthmemories .monthly {
                padding: 3EM 0;
            }

            #monthmemories header>div {
                margin: -1.5REM 0;
            }

            #monthmemories .monthly>div {
                width: 40%;
                padding: 0 1EM;
            }

            .imagecrop::after {
                height: 150px;
                width: 100%;
                bottom: 0;
            }

            #monthmemories .monthly>div.bullet {
                font-size: 2.3EM;
            }

        }

        @media screen and (min-width: 721px) and (max-width: 960px) {
            #tenpeople {
                background: rgba(0, 0, 0, 0) url(<?= get_theme_file_uri();?>/includes/memories/images/people-background-tenpeople.png) center center no-repeat;
                background-size: cover;
            }

            #tenpeople .body {
                display: flex;
                flex-wrap: wrap;
                margin-top: 50px;
                margin-bottom: 30px;
                justify-content: center;
            }

            #tenpeople .column {
                width: 175px;
                height: auto;
                margin: 0 -20px;
                /* margin-bottom: 20px; */
            }

            #tenpeople .red-header,
            #tenpeople .white-header {
                font-size: 2.5EM;
            }

            #tenpeople img {
                display: block;
                height: auto;
                transition: transform 0.5s;
            }

            #tenpeople .row {
                margin-right: -15px;
                margin-left: 38px;
                /* height: 200px; */
            }

            #tenpeople .row:nth-child(1) {
                margin-left: 84px;
            }

            #tenpeople .column:nth-child(even) {
                margin-top: -14px;
                margin-left: -19px;
            }

            #tenpeople .column:nth-child(odd) {
                margin-left: -25px;
            }

            #monthmemories .monthly {
                padding: 1.5EM 0;
            }

            #monthmemories .monthly>div {
                width: 45%;
                padding: 0 0.3EM;
            }

            .imagecrop::after {
                height: 100px;
                width: 100%;
                bottom: 0;
            }

            #monthmemories .monthly>div.bullet {
                font-size: 1.6EM;
            }

            #monthmemories .monthly>div.bullet article, 
            #monthmemories .monthly>div.bullet footer,
            #monthmemories .bullet ul li {
                font-size: 1REM;
            }
        }

        @media screen and (min-width: 961px) and (max-width: 1280px) {

            #tenpeople .body {
                display: flex;
                flex-wrap: wrap;
                margin-top: 75px;
                margin-bottom: 30px;
                justify-content: center;
            }

            #tenpeople {
                background: rgba(0, 0, 0, 0) url('<?= get_theme_file_uri();?>/includes/memories/images/people-background-tenpeople.png') top center no-repeat;
                background-size: cover;
            }

            #tenpeople .red-header,
            #tenpeople .white-header {
                font-size: 3EM;
            }

            #tenpeople img {
                display: block;
                height: auto;
                transition: transform 0.5s;
            }

            #tenpeople .row {
                margin-right: -15px;
                margin-left: 38px;
                /* height: 200px; */
            }

            #tenpeople .row:nth-child(1) {
                margin-left: 84px;
            }

            #tenpeople .column {
                width: 210px;
                height: auto;
                margin: 0 -24px;
                /* margin-bottom: 20px; */
            }

            #tenpeople .column:nth-child(even) {
                margin-top: -14px;
                margin-left: -19px;
            }

            #tenpeople .column:nth-child(odd) {
                margin-left: -25px;
            }

            #monthmemories .monthly {
                padding: 1.5EM 0;
            }

            #monthmemories .monthly>div {
                width: 45%;
                padding: 0 0.3EM;
            }

            .imagecrop::after {
                height: 100px;
                width: 100%;
                bottom: 0;
            }

            #monthmemories .monthly>div.bullet {
                font-size: 1.6EM;
            }

            #monthmemories .monthly>div.bullet article, 
            #monthmemories .monthly>div.bullet footer,
            #monthmemories .bullet ul li {
                font-size: 1.1REM;
            }


        }

        @media screen and (min-width: 721px) {

            #tenpeople .peoplecontainer.mobile {
                display: none;
            }
            #tenpeople .peoplecontainer.laptop {
                display: block;
            }
            #monthmemories .monthly.even {
                flex-direction: row-reverse;
            }
            #monthmemories .monthly .frame {
                position: relative;
                border: 4px solid red;
                width: 100%;
                height: var(--img-height);

            }

            #monthmemories .monthly .frame a {
                aspect-ratio: 3/2;
                width: 100%;
                height: 100%;
            }
            #monthmemories .imagecrop:hover{
                transition: all .4s;
                transform: translate(17px, -17px);
            }
            #monthmemories .monthly.odd .img,
            #monthmemories .monthly.even .bullet section {
                justify-content: end;
            }

            #monthmemories .monthly.even .bullet {
                text-align: right;
                padding-right: 1.5EM;
            }

            #monthmemories .monthly.even .bullet ul li::after {
                content: '\2022';
                font-size: 1.5REM;
                margin-left: 0.5EM;
            }
            #monthmemories .monthly.even .bullet ul li.nostyle::after {
                content: '';
                font-size: 1.5REM;
                margin-left: 0.5EM;
            }

            #monthmemories .monthly.odd .bullet ul li::before {
                content: '\2022';
                font-size: 1.5REM;
                margin-right: 0.5EM;
            }
        }

        @media screen and (max-width: 720px) {
            #memories .memories2023-main-banner{
                text-align:center;
                object-fit: cover;
                display: flex;
                justify-content:center;
            }
            #memories .memories2023-main-banner img{
                min-width: 145%;
                height: auto;
            }

            #tenpeople .peoplecontainer.mobile {
                display: inherit;
            }
            #tenpeople .peoplecontainer.laptop {
                display: none;
            }
            #memories .td-container,
            .tdc-row {
                width: 100%;
                padding-left: 0px;
                padding-right: 0px;
            }

            #tenpeople .body {
                display: flex;
                flex-wrap: wrap;
                margin-top: 25px;
                margin-bottom: 0px;
                justify-content: center;
            }

            #tenpeople {
                height: auto;
                background: rgba(0, 0, 0, 0) url('<?php echo  get_theme_file_uri(); ?>/includes/memories/images/people-background-tenpeople.png') center left no-repeat;
                background-size: cover;
                padding-top: 50px;
                padding-bottom: 50px;
            }

            #tenpeople .peoplecontainer {
                margin-left: 10px;
            }

            #tenpeople .title {
                padding-top: 0;
            }

            #tenpeople .red-header {
                font-size: 1.5em;
            }

            #tenpeople .white-header {
                font-size: 1.5em;
            }

            #tenpeople img {
                display: block;
                height: 100%;
                width: 200px;
                transition: transform 0.5s;
            }

            #tenpeople .row {
                display: inline-block;
                margin: 10px -25px;
            }

            #monthmemories .monthly {
                display: flex;
                flex-direction: column;
                justify-content: center;
                color: aliceblue;
            }

            #monthmemories .monthly {
                width: 100%;
                padding: 1.5EM 1EM;
            }

            #monthmemories .monthly>div {
                padding: 0 0.3EM;
            }

            .imagecrop::after {
                height: 100px;
                width: 100%;
                bottom: 0;
            }

            #monthmemories .monthly>div.bullet {
                font-size: 1.6EM;
                margin-top: 2EM;
            }

            #monthmemories .monthly>div.bullet article, 
            #monthmemories .monthly>div.bullet footer,
            #monthmemories .bullet ul li {
                font-size: 1REM;
            }

            #monthmemories .monthly .frame {
                position: relative;
                border: 4px solid red;
                aspect-ratio: 3/2;
                width: 98%;
            }

            #monthmemories .monthly .frame a {
                top: 1EM;
                right: 1EM;
                aspect-ratio: 3/2;
                width: 100%;
                height: 100%;
            }

            #monthmemories .imagecrop:hover{
                transition: all .4s;
                transform: translate(13px, -13px);
            }

            #monthmemories .monthly .frame a img {
                aspect-ratio: 3/2;
                width: 100%;
            }

            #monthmemories .monthly .bullet ul li::before {
                content: '\2022';
                font-size: 1.5REM;
                margin-right: 0.5EM;
            }
            #monthmemories .monthly .bullet ul li.nostyle::before {
                content: '';
                font-size: 1.5REM;
                margin-right: 0.5EM;
            }
            #monthmemories .monthly .img {
                justify-content: end;
            }
            #monthmemories .monthly .frame .imagecrop{
                top: 0.8EM;
                right: 0.8EM;
            }        
        }
        @media (min-width: 992px) {
            #ud-dfp-ad-pos-mtw_col_belt {
                width: 100%;
                height: 275px;
            }
        }
    </style>

<?php

}
add_action('wp_head', 'load_memories_css');


function people_memory()
{
    $file_content = '<div id="tenpeople" >
            <div class="title">
            <span class="red-header">10 คนดัง สะเทือนสังคม</span><br/>
            <span class="white-header">THE MOST INFLUENTIAL PEOPLE OF 2023</span>
            </div>
            <div class="body">
                <div class="peoplecontainer laptop">
                    <div class="row">
                        <div class="column">
                            <a href="'. get_site_url() .'/memorires/2023/the-most-influential-people/article_732524" target="_blank"><img src="' . get_theme_file_uri() . '/includes/memories/images/person1.png" alt="memories"></a>
                        </div>
                        <div class="column">
                            <a href="'. get_site_url() .'/memorires/2023/the-most-influential-people/article_732526" target="_blank"><img src="' . get_theme_file_uri() . '/includes/memories/images/person2.png" alt="memories"></a>
                        </div>
                        <div class="column">
                            <a href="'. get_site_url() .'/memorires/2023/the-most-influential-people/article_732529" target="_blank"><img src="' . get_theme_file_uri() . '/includes/memories/images/person3.png" alt="memories"></a>
                        </div>
                        <div class="column">
                            <a href="'. get_site_url() .'/memorires/2023/the-most-influential-people/article_732531" target="_blank"><img src="' . get_theme_file_uri() . '/includes/memories/images/person4.png" alt="memories"></a>
                        </div>
                        <div class="column">
                            <a href="'. get_site_url() .'/memorires/2023/the-most-influential-people/article_732533" target="_blank"><img src="' . get_theme_file_uri() . '/includes/memories/images/person5.png" alt="memories"></a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <a href="'. get_site_url() .'/memorires/2023/the-most-influential-people/article_732535" target="_blank"><img src="' . get_theme_file_uri() . '/includes/memories/images/person6.png" alt="memories"></a>
                        </div>
                        <div class="column">
                            <a href="'. get_site_url() .'/memorires/2023/the-most-influential-people/article_732539" target="_blank"><img src="' . get_theme_file_uri() . '/includes/memories/images/person7.png" alt="memories"></a>
                        </div>
                        <div class="column">
                            <a href="'. get_site_url() .'/memorires/2023/the-most-influential-people/article_732537" target="_blank"><img src="' . get_theme_file_uri() . '/includes/memories/images/person8.png" alt="memories"></a>
                        </div>
                        <div class="column">
                            <a href="'. get_site_url() .'/memorires/2023/the-most-influential-people/article_732549" target="_blank"><img src="' . get_theme_file_uri() . '/includes/memories/images/person9.png" alt="memories"></a>
                        </div>
                        <div class="column">
                            <a href="'. get_site_url() .'/memorires/2023/the-most-influential-people/article_732551" target="_blank"><img src="' . get_theme_file_uri() . '/includes/memories/images/person10.png" alt="memories"></a>
                        </div>
                    </div>
                </div>
                <div class="peoplecontainer mobile">
                    <div class="row">
                        <div class="column">
                            <a href="'. get_site_url() .'/memorires/2023/the-most-influential-people/article_732524" target="_blank"><img src="' . get_theme_file_uri() . '/includes/memories/images/person1.png" alt="memories"></a>
                        </div>
                        
                        <div class="column">
                            <a href="'. get_site_url() .'/memorires/2023/the-most-influential-people/article_732529" target="_blank"><img src="' . get_theme_file_uri() . '/includes/memories/images/person3.png" alt="memories"></a>
                        </div>
                        
                        <div class="column">
                            <a href="'. get_site_url() .'/memorires/2023/the-most-influential-people/article_732533" target="_blank"><img src="' . get_theme_file_uri() . '/includes/memories/images/person5.png" alt="memories"></a>
                        </div>
                        <div class="column">
                            <a href="'. get_site_url() .'/memorires/2023/the-most-influential-people/article_732539" target="_blank"><img src="' . get_theme_file_uri() . '/includes/memories/images/person7.png" alt="memories"></a>
                        </div>
                        <div class="column">
                            <a href="'. get_site_url() .'/memorires/2023/the-most-influential-people/article_732549" target="_blank"><img src="' . get_theme_file_uri() . '/includes/memories/images/person9.png" alt="memories"></a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="column">
                            <a href="'. get_site_url() .'/memorires/2023/the-most-influential-people/article_732526" target="_blank"><img src="' . get_theme_file_uri() . '/includes/memories/images/person2.png" alt="memories"></a>
                        </div>
                        <div class="column">
                            <a href="'. get_site_url() .'/memorires/2023/the-most-influential-people/article_732531" target="_blank"><img src="' . get_theme_file_uri() . '/includes/memories/images/person4.png" alt="memories"></a>
                        </div>
                        <div class="column">
                            <a href="'. get_site_url() .'/memorires/2023/the-most-influential-people/article_732535" target="_blank"><img src="' . get_theme_file_uri() . '/includes/memories/images/person6.png" alt="memories"></a>
                        </div>

                        
                        <div class="column">
                            <a href="'. get_site_url() .'/memorires/2023/the-most-influential-people/article_732537" target="_blank"><img src="' . get_theme_file_uri() . '/includes/memories/images/person8.png" alt="memories"></a>
                        </div>
                        
                        <div class="column">
                            <a href="'. get_site_url() .'/memorires/2023/the-most-influential-people/article_732551" target="_blank"><img src="' . get_theme_file_uri() . '/includes/memories/images/person10.png" alt="memories"></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>';

    return $file_content;
}
add_shortcode('people_memory', 'people_memory');


function news_memory()
{
    $bullet_content = '
        <div id="monthmemories">
            <div style="width:100%;padding:1EM 0EM;">
                <img src="' . get_theme_file_uri() . '/includes/memories/images/100memories.png"  style="display:block;margin-left: auto; margin-right: auto">
            </div>
            
            <div class="monthly odd">
                <div class="img">
                    <div class="frame">
                    <a href="'. get_site_url() .'memorires/2023/biggest-news-stories/article_732554" target="_blank" target="_blank">
                        <div class="imagecrop">
                            <img src="' . get_theme_file_uri() . '/includes/memories/images/jan.png" alt="memories">
                        </div>
                    </a>
                    </div>
                </div>
                <div class="bullet">
                    <section>
                    <header>
                        <div class="month">JANUARY</div>
                        <div class="year">2023</div>
                    </header>
                    <article>
                        <ul>
                            <li>ตร.ไทยรีดไถเงินดาราไต้หวัน</li>
                            <li>ทรงอย่างแบด เพลงดังฟันน้ำนม</li>
                            <li>2 ป. ร้าวลึก ความขัดแย้ง ตู่-ป้อม</li>
                            <li>ช้อปงานวิจัย สะเทือนอุดมศึกษา</li>
                            <li>เจ้าฟ้าพัชรกิติยาภา ทรงพระประชวร</li>
                        </ul>
                    </article>
                    <a href="'. get_site_url() .'memorires/2023/biggest-news-stories/article_732554" class="" target="_blank">
                    <footer>SEE MORE >></footer>
                    </a>
                    </section>
                </div>
            </div>

            <div class="monthly even">
                <div class="img">
                    <div class="frame">
                    <a href="'. get_site_url() .'memorires/2023/biggest-news-stories/article_732566" target="_blank" target="_blank">
                        <div class="imagecrop">
                            <img src="' . get_theme_file_uri() . '/includes/memories/images/feb.png" alt="memories">
                        </div>
                    </a>
                    </div>
                </div>
                <div class="bullet">
                    <section>
                    <header>
                        <div class="month">FEBUARY</div>
                        <div class="year">2023</div>
                    </header>
                    <article>
                        <ul>
                            <li>เด้งอธิบดีอุทยานฯ เรียกรับ 5 ล้าน</li>
                            <li>แฉ ส.ว.ทรงเอ เจ้าตัวร่ำไห้ท้าสาบาน</li>
                            <li>ซักฟอกประยุทธ์ครั้งสุดท้าย</li>
                            <li>กู้เรือสุโขทัย 200 ล้าน</li>
                            <li>“นิ่ม” ทิ้งลูกลงแม่น้ำ</li>
                        </ul>
                    </article>
                    <a href="'. get_site_url() .'memorires/2023/biggest-news-stories/article_732566" class="" target="_blank">
                    <footer>SEE MORE >></footer>
                    </a>
                    </section>
                </div>
            </div>

            <div class="monthly odd">
                <div class="img">
                    <div class="frame">
                    <a href="'. get_site_url() .'memorires/2023/biggest-news-stories/article_732568" target="_blank" target="_blank">
                        <div class="imagecrop">
                            <img src="' . get_theme_file_uri() . '/includes/memories/images/mar.png" alt="memories">
                        </div>
                    </a>
                    </div>
                </div>
                <div class="bullet">
                    <section>
                    <header>
                        <div class="month">MARCH</div>
                        <div class="year">2023</div>
                    </header>
                    <article>
                        <ul>
                            <li>เพื่อไทย ตั้งเป้าแลนด์สไลด์ 310 ที่นั่ง</li>
                            <li>ชู 3 นิ้ว ประท้วงประยุทธ์ ทั่วสารทิศ</li>
                            <li>ท่อซีเซียมหายที่ปราจีน ผวากัมมันตรังสี</li>
                            <li>ปฏิบัติการ "สยบสารวัตรคลั่ง"</li>
                            <li>ยุบสภา กาบัตร 14 พ.ค.</li>
                        </ul>
                    </article>
                    <a href="'. get_site_url() .'memorires/2023/biggest-news-stories/article_732568" class="" target="_blank">
                    <footer>SEE MORE >></footer>
                    </a>
                    </section>
                </div>
            </div>

            <div class="monthly even">
                <div class="img">
                    <div class="frame">
                    <a href="'. get_site_url() .'memorires/2023/biggest-news-stories/article_732570" target="_blank" target="_blank">
                        <div class="imagecrop">
                            <img src="' . get_theme_file_uri() . '/includes/memories/images/apr.png" alt="memories">
                        </div>
                    </a>
                    </div>
                </div>
                <div class="bullet">
                    <section>
                    <header>
                        <div class="month">APRIL</div>
                        <div class="year">2023</div>
                    </header>
                    <article>
                        <ul>
                            <li>แจกดิจิทัลคนละหมื่นสุดฮือฮา</li>
                            <li>เพื่อไทย ไม่เอา 3 ป. - ไม่จับมือ 2 ลุง</li>
                            <li>โพล “มติชนxเดลินิวส์” ก้าวไกลพลิกนำ</li>
                            <li>"แอม ไซยาไนต์" วางยาเหยื่อ ดังระดับโลก</li>
                            <li>ดราม่า ความสุขอยู่ที่ใจ กินไข่ต้มสอนเด็ก</li>
                        </ul>
                    </article>
                    <a href="'. get_site_url() .'memorires/2023/biggest-news-stories/article_732570" class="" target="_blank">
                    <footer>SEE MORE >></footer>
                    </a>
                    </section>
                </div>
            </div>

            <div class="monthly odd">
                <div class="img">
                    <div class="frame">
                    <a href="'. get_site_url() .'memorires/2023/biggest-news-stories/article_732572" target="_blank" target="_blank">
                        <div class="imagecrop">
                            <img src="' . get_theme_file_uri() . '/includes/memories/images/may.png" alt="memories">
                        </div>
                    </a>
                    </div>
                </div>
                <div class="bullet">
                    <section>
                    <header>
                        <div class="month">MAY</div>
                        <div class="year">2023</div>
                    </header>
                    <article>
                        <ul>
                            <li>ก้าวไกลชนะเลือกตั้ง แลนด์สไลด์ กทม.</li>
                            <li>เมาแล้วขับดับชีวิตทางการเมือง</li>
                            <li>ส่วยสติ๊กเกอร์-เด้งผู้การทางหลวง</li>
                            <li>สมาคม อมเงินวัด 280 ล้าน</li>
                            <li>ก้าวไกลประกาศตั้งรัฐบาล 313 เสียง</li>
                        </ul>
                    </article>
                    <a href="'. get_site_url() .'memorires/2023/biggest-news-stories/article_732572" class="" target="_blank">
                    <footer>SEE MORE >></footer>
                    </a>
                    </section>
                </div>
            </div>

            <div class="monthly even">
                <div class="img">
                    <div class="frame">
                    <a href="'. get_site_url() .'memorires/2023/biggest-news-stories/article_732575" target="_blank" target="_blank">
                        <div class="imagecrop">
                            <img src="' . get_theme_file_uri() . '/includes/memories/images/jun.png" alt="memories">
                        </div>
                    </a>
                    </div>
                </div>
                <div class="bullet">
                    <section>
                    <header>
                        <div class="month">JUNE</div>
                        <div class="year">2023</div>
                    </header>
                    <article>
                        <ul>
                            <li>ปลุกผีไอทีวี คดีหุ้นสื่อสกัด “พิธา”</li>
                            <li>ทางเลื่อนดอนเมืองทำขาขาด ข่าวดังทั่วโลก</li>
                            <li>ถังดับเพลิงระเบิดคร่า ‘น้องเบนซ์’</li>
                            <li>ไทยถูกตำหนิ เชิญอาเซียนหารือปมพม่า</li>
                            <li>ผู้การชลบุรี เรียกรับ 140 ล้าน</li>
                        </ul>
                    </article>
                    <a href="'. get_site_url() .'memorires/2023/biggest-news-stories/article_732575" class="" target="_blank">
                    <footer>SEE MORE >></footer>
                    </a>
                    </section>
                </div>
            </div>

            <div class="monthly odd">
                <div class="img">
                    <div class="frame">
                    <a href="'. get_site_url() .'memorires/2023/biggest-news-stories/article_732579" target="_blank" target="_blank">
                        <div class="imagecrop">
                            <img src="' . get_theme_file_uri() . '/includes/memories/images/jul.png" alt="memories">
                        </div>
                    </a>
                    </div>
                </div>
                <div class="bullet">
                    <section>
                    <header>
                        <div class="month">JULY</div>
                        <div class="year">2023</div>
                    </header>
                    <article>
                        <ul>
                            <li>สมาคมบอลฯเดือด “สมยศ” ประกาศไขก๊อก</li>
                            <li>สะพานข้ามแยกถล่ม ช็อกกลางกรุง-ดับ 3</li>
                            <li>ศึกชิง ประธานสภาฯ เพื่อไทย ท้าชน ก้าวไกล</li>
                            <li>มติ 2 สภา ไม่ให้โหวตพิธารอบ 2</li>
                            <li>เพื่อไทยจับมือ 2 ลุง ช็อกมิ้น เมนูตั้งรัฐบาล</li>
                        </ul>
                    </article>
                    <a href="'. get_site_url() .'memorires/2023/biggest-news-stories/article_732579" class="" target="_blank">
                    <footer>SEE MORE >></footer>
                    </a>
                    </section>
                </div>
            </div>

            <div class="monthly even">
                <div class="img">
                    <div class="frame">
                    <a href="'. get_site_url() .'memorires/2023/biggest-news-stories/article_732581" target="_blank" target="_blank">
                        <div class="imagecrop">
                            <img src="' . get_theme_file_uri() . '/includes/memories/images/aug.png" alt="memories">
                        </div>
                    </a>
                    </div>
                </div>
                <div class="bullet">
                    <section>
                    <header>
                        <div class="month">AGUST</div>
                        <div class="year">2023</div>
                    </header>
                    <article>
                        <ul>
                            <li>“ทักษิณ” กลับไทยครั้งแรก</li>
                            <li>สว.สายตู่ เทคะแนน “เศรษฐา” นั่งนายกฯ</li>
                            <li>"ท่านอ้น-ท่านอ่อง" กลับไทย ฝันที่เป็นจริง</li>
                            <li>สิ้น "นิธิ เอียวศรีวงศ์" ปราชญ์แห่งยุคสมัย</li>
                            <li>ฮือต้านรัฐแอบปรับเกณฑ์เบี้ยผู้สูงอายุ</li>
                        </ul>
                    </article>
                    <a href="'. get_site_url() .'memorires/2023/biggest-news-stories/article_732581" class="" target="_blank">
                    <footer>SEE MORE >></footer>
                    </a>
                    </section>
                </div>
            </div>

            <div class="monthly odd">
                <div class="img">
                    <div class="frame">
                    <a href="'. get_site_url() .'memorires/2023/biggest-news-stories/article_732583" target="_blank" target="_blank">
                        <div class="imagecrop">
                            <img src="' . get_theme_file_uri() . '/includes/memories/images/sep.png" alt="memories">
                        </div>
                    </a>
                    </div>
                </div>
                <div class="bullet">
                    <section>
                    <header>
                        <div class="month">SEPTEMBER</div>
                        <div class="year">2023</div>
                    </header>
                    <article>
                        <ul>
                            <li>โปรดเกล้าฯ ครม.เศรษฐา</li>
                            <li>ดวงฤทธิ์ จัด “ปาขี้” รับผิดชอบคำพูด</li>
                            <li>“กำนันนก” สั่งตาย  “สารวัตรทางหลวง”</li>
                            <li>“พิธา” ลาออก ส่งไม้ต่อ “ชัยธวัช” นำทัพก้าวไกล</li>
                            <li>ศรีเทพ คว้า มรดกโลก</li>
                        </ul>
                    </article>
                    <a href="'. get_site_url() .'memorires/2023/biggest-news-stories/article_732583" class="" target="_blank">
                    <footer>SEE MORE >></footer>
                    </a>
                    </section>
                </div>
            </div>

            <div class="monthly even">
                <div class="img">
                    <div class="frame">
                    <a href="'. get_site_url() .'memorires/2023/biggest-news-stories/article_732585" target="_blank" target="_blank">
                        <div class="imagecrop">
                            <img src="' . get_theme_file_uri() . '/includes/memories/images/oct.png" alt="memories">
                        </div>
                    </a>
                    </div>
                </div>
                <div class="bullet">
                    <section>
                    <header>
                        <div class="month">OCTOBER</div>
                        <div class="year">2023</div>
                    </header>
                    <article>
                        <ul>
                            <li>นร.วัย 14 บุกยิง พารากอน</li>
                            <li>เงินดิจิทัลอลวน กระแสต้านพุ่ง</li>
                            <li>แรงงานไทยสังเวยชีวิตจากสงคราม</li>
                            <li>จำคุกอานนท์ นำภา 4 ปี</li>
                            <li>ขับ 2 ส.ส.ก้าวไกล คุกคามทางเพศ</li>
                        </ul>
                    </article>
                    <a href="'. get_site_url() .'memorires/2023/biggest-news-stories/article_732585" class="" target="_blank">
                    <footer>SEE MORE >></footer>
                    </a>
                    </section>
                </div>
            </div>

            <div class="monthly odd">
                <div class="img">
                    <div class="frame">
                    <a href="'. get_site_url() .'memorires/2023/biggest-news-stories/article_732587" target="_blank" target="_blank">
                        <div class="imagecrop">
                            <img src="' . get_theme_file_uri() . '/includes/memories/images/nov.png" alt="memories">
                        </div>
                    </a>
                    </div>
                </div>
                <div class="bullet">
                    <section>
                    <header>
                        <div class="month">NOVEMBER</div>
                        <div class="year">2023</div>
                    </header>
                    <article>
                        <ul>
                            <li>คนไทยปลุก แบนเที่ยวเกาหลี</li>
                            <li>ฆ่านศ.อุเทนฯ-ครูเจี๊ยบ ตะลึงจัดตั้งเป็นองค์กร</li>
                            <li>"ตั๋วผู้กำกับ" สะเทือน "เศรษฐา"</li>
                            <li>เปิดทรัพย์สินประยุทธ์-ประวิตร รอบ10ปี</li>
                            <li>โปรดเกล้าฯ "ประยุทธ์" องคมตรี</li>
                        </ul>
                    </article>
                    <a href="'. get_site_url() .'memorires/2023/biggest-news-stories/article_732587" class="" target="_blank">
                    <footer>SEE MORE >></footer>
                    </a>
                    </section>
                </div>
            </div>

            <div class="monthly even">
                <div class="img">
                    <div class="frame">
                    <a href="'. get_site_url() .'memorires/2023/biggest-news-stories/article_732589" target="_blank" target="_blank">
                        <div class="imagecrop">
                            <img src="' . get_theme_file_uri() . '/includes/memories/images/dec.png" alt="memories">
                        </div>
                    </a>
                    </div>
                </div>
                <div class="bullet">
                    <section>
                    <header>
                        <div class="month">DECEMBER</div>
                        <div class="year">2023</div>
                    </header>
                    <article>
                        <ul>
                            <li>ทัวร์มรณะคร่า 16 ศพ</li>
                            <li>สิ้น "หมอกฤตไท" เจ้าของเพจ สู้ดิวะ</li>
                            <li>ชุลมุนศึกชิงหัวหน้าพรรคประชาธิปัตย์</li>
                            <li>ปีติ "ราชินี" ทรงแข่งเรือใบ</li>
                            <li>คุก 6 ปี "ไอซ์ รักชนก" คดี 112</li>
                        </ul>
                    </article>
                    <a href="'. get_site_url() .'memorires/2023/biggest-news-stories/article_732589" class="" target="_blank">
                    <footer>SEE MORE >></footer>
                    </a>
                    </section>
                </div>
            </div>

        </div>';
    return $bullet_content;
}
add_shortcode('news_memory', 'news_memory');
