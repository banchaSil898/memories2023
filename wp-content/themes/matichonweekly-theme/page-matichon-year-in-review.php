<?php
/* Template Name: Marichon year in review */
?>
<link href="https://fonts.googleapis.com/css?family=Kanit" rel="stylesheet" />
<style>
  #year_in_review_2023{
    margin: 0;
    padding: 0;
    width:100vw;
    height:100vh;
    overflow: hidden;
  }
  #year_in_review_2023 .main-container{
    height: 100%;
    display: grid;
    grid-template-rows: 60%;
    grid-template-columns: minmax(43em, 50%);
    position: relative;
  }
  #year_in_review_2023 .main-container>div{
    /* border: solid #F44 1px; */
  }
  #year_in_review_2023 .container{
    display: flex;
  }
  #year_in_review_2023 .container > .contain{
    display:flex;
    align-items: flex-end;
    flex-direction: column;
    width: 100%;
    margin: 1em;
  }
  #year_in_review_2023 .contain.flex-align-bottom{
    justify-content: flex-end;
  }
  #year_in_review_2023 .contain > div{
    display:flex;
    text-align: left;
  }
  #year_in_review_2023 .detail, .button-line{
    font-size: .8em;
    width: 37em;
    color: #212121;
    /* Light */
    font-family: Kanit;
    font-size: 16px;
    font-style: normal;
    font-weight: 300;
    line-height: normal;
  }
  #year_in_review_2023 .detail{
    margin: 2.25em 0.7em 2.25em 0;
  }
  #year_in_review_2023 .button{
    text-align: center;
  }
  #year_in_review_2023 .container .contain  a {
    text-decoration: none;
    color: white;
    display: flex;
    padding: 12px 32px;
    align-items: center;
    gap: 18px;
    margin: 0 .5em;
    border-radius: 120px;
  }
  #year_in_review_2023 .black-background{
    background: #212121;
    box-shadow: 0px 8px 9.9px 0px #D9D9D9;
  }
  #year_in_review_2023 .red-background{
    background: #EB1C24;
    box-shadow: 0px 8px 9.9px 0px rgba(235, 28, 36, 0.19);
  }
  #year_in_review_2023 .img-cover{
    position: absolute;
    top: calc(50% - 350px);
    left: 50vw;
  }
  #year_in_review_2023 .img-cover {
    & img{
      height: auto;
      width: 100%
    }
  }
  #year_in_review_2023 .img-watermark{
    position: absolute;
    max-width: 820px;
    max-height:500px;
    width: 45vw;
    height: auto;
    left: 140px;
  }
  
  @media (orientation: portrait), screen and (width < 921px){
    #year_in_review_2023 .main-container{
      display: flex;
      flex-direction: column;
      margin-top: 5vh;
    }
    #year_in_review_2023 .main-container .contain{
      align-items: center;
    }
    #year_in_review_2023 .container .contain  a {
      padding: 12px 24px;
    }
    #year_in_review_2023 .contain{
      & div{
        width:100%;
        text-align: center;
        & img{
          width: 100%;
        }
      }
      & .detail, .button-line{
        font-size: 1em;
      }
      & .button-line{
        flex-direction: column;
        & a{

          margin: 12px 0;
        }
      }
    }
    #year_in_review_2023 .img-watermark{
      display:none;
    }
    #year_in_review_2023 .detail, #year_in_review_2023 .button-line{
      width: 100%;
      justify-content: center;
    }
    #year_in_review_2023 .detail {
      margin: 0.625em 0;
    }
    #year_in_review_2023 .img-cover{
      display: flex;
      position: static;
      justify-content: center;
      width: 100%;
      margin-top:3em;
      & img{
        height: auto;
        width: 90%;
      }
    }
  }
  @media (width<= 360px) and (orientation: portrait){
    .detail, .button-line{
      font-size: .7em !important;
    }
  }

</style>
<div id="year_in_review_2023" style="background:url('https://www.matichon.co.th/wp-content/uploads/2023/12/year-in-review-2023-bg.png') no-repeat fixed center;background-size: cover;">
  <div class="img-watermark">
    <img src="https://www.matichon.co.th/wp-content/uploads/2023/12/water_mark.png" style="opacity:0.6" width="100%" height="auto" alt="">
  </div>
  <div class="main-container">
    <div class="container" style="z-index: 2;">
      <div class="contain flex-align-bottom">
        <div><img src="https://www.matichon.co.th/wp-content/uploads/2023/12/year-in-review-2023-label.png" alt="" style="max-width:100%;height:auto;"></div>
        <div class="detail" style="text-shadow: 1px 1px white;">มติชนมอบของขวัญแก่นักอ่าน ในวาระก้าวเข้าสู่ปีที่ 47 แจกฟรี E-book</br>
        มติชนบันทึกประเทศไทย 2566 หนังสือที่รวบรวมประเด็นข่าวเหตุการณ์สำคัญที่เป็นจุดเปลี่ยน</br>
        ของประเทศไทย เพื่อให้ทบทวนเรื่องราวที่ผ่านมา และพร้อมที่จะก้าวต่อไปในปีข้างหน้า</div>
        <div class="button-line">
          <a href="<?= get_site_url() ?>/book-for-gift" class="red-background" targer="_self">
            <div class="button">
              ดาวน์โหลด E-Book ฟรี
            </div>
          </a>
          <a href="https://www.matichon.co.th/home" class="black-background" target="_self">
            <div class="button">
              เข้าสู่เว็บมติชน
            </div>
          </a>
        </div>
      </div>
    </div>
    <div class="img-cover">
      <img src="https://www.matichon.co.th/wp-content/uploads/2024/01/year-in-review-2023-cover.png" alt="" hieght="100%">
    </div>
  </div>
</div>