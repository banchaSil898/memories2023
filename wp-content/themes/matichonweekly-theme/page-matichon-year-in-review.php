<?php
/* Template Name: Marichon year in review */
?>
<link href="https://fonts.googleapis.com/css?family=Kanit" rel="stylesheet" />
<style>
  body{
    margin: 0;
    padding: 0;
    width:100vw;
    height:100vh;
    overflow: hidden;
  }
  .main-container{
    height: 100%;
    display: grid;
    grid-template-rows: 60%;
    grid-template-columns: minmax(43EM, 50%);
    position: relative;
  }
  .main-container>div{
    /* border: solid #F44 1px; */
  }
  .container{
    display: flex;
  }
  .container > .contain{
    display:flex;
    align-items: flex-end;
    flex-direction: column;
    width: 100%;
    margin: 1EM;
  }
  .contain.flex-align-bottom{
    justify-content: flex-end;
  }
  .contain > div{
    display:flex;
    text-align: left;
  }
  .detail, .button-line{
    font-size: .8EM;
    width: 37EM;
    color: #212121;
    /* Light */
    font-family: Kanit;
    font-size: 16px;
    font-style: normal;
    font-weight: 300;
    line-height: normal;
  }
  .detail{
    margin: 2.25EM 0.7EM 2.25EM 0;
  }
  .button{
    text-align: center;
  }
  .container .contain  a {
    text-decoration: none;
    color: white;
    display: flex;
    padding: 12px 32px;
    align-items: center;
    gap: 18px;
    margin: 0 .5EM;
    border-radius: 120px;
  }
  .black-background{
    background: #212121;
    box-shadow: 0px 8px 9.9px 0px #D9D9D9;
  }
  .red-background{
    background: #EB1C24;
    box-shadow: 0px 8px 9.9px 0px rgba(235, 28, 36, 0.19);
  }
  .img-cover{
    position: absolute;
    top: calc(50% - 350px);
    left: 50vw;
  }
  .img-cover {
    z-index: -1;
    & img{
      height: auto;
      width: 100%
    }
  }
  .img-watermark{
    position: absolute;
    max-width: 820px;
    max-height:500px;
    width: 45vw;
    height: auto;
    left: 140px;
  }
  @media (orientation: portrait), screen and (width < 921px){
    .main-container{
      display: flex;
      flex-direction: column;
      margin-top: 5vh;
    }
    .main-container .contain{
      align-items: center;
    }
    .container .contain  a {
      padding: 24px 48px;
    }
    .contain{
      & div{
        width:100%;
        text-align: center;
        & img{
          width: 100%;
        }
      }
      & .detail, .button-line{
        font-size: 2EM;
      }
      & .button-line{
        flex-direction: column;
        & a{

          margin: 12px 0;
        }
      }
    }
    .img-watermark{
      display:none;
    }
    .detail, .button-line{
      width: 100%;
      justify-content: center;
    }
    .detail {
      margin: 0.625EM 0;
    }
    .img-cover{
      display: flex;
      position: static;
      justify-content: center;
      width: 100%;
      margin-top:3EM;
      & img{
        height: auto;
        width: 90%;
      }
    }
  }
  @media (width<= 360px) and (orientation: portrait){
    .detail, .button-line{
      font-size: .7EM !important;
    }
  }

</style>
<body style="background:url('https://www.matichon.co.th/wp-content/uploads/2023/12/year-in-review-2023-bg.png') no-repeat fixed center;background-size: cover;">
  <div class="img-watermark">
    <img src="https://www.matichon.co.th/wp-content/uploads/2023/12/water_mark.png" style="opacity:0.6" width="100%" height="auto" alt="">
  </div>
  <div class="main-container">
    <div class="container ">
      <div class="contain flex-align-bottom">
        <div><img src="https://www.matichon.co.th/wp-content/uploads/2023/12/year-in-review-2023-label.png" alt="" style="max-width:100%;height:auto;"></div>
        <div class="detail" style="text-shadow: 1px 1px white;">มติชนมอบของขวัญแก่นักอ่าน ในวาระก้าวเข้าสู่ปีที่ 47 แจกฟรี E-book</br>
        มติชนบันทึกประเทศไทย 2566 หนังสือที่รวบรวมประเด็นข่าวเหตุการณ์สำคัญที่เป็นจุดเปลี่ยน</br>
        ของประเทศไทย เพื่อให้ทบทวนเรื่องราวที่ผ่านมา และพร้อมที่จะก้าวต่อไปในปีข้างหน้า</div>
        <div class="button-line">
          <a href="http://localhost/matichonweekly/matichon-year-in-review-frame-e-book-register" class="red-background" targer="_self">
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
    <div class="container">
      <div class="contain">
        
      </div>
    </div>
    <div class="img-cover">
      <img src="https://www.matichon.co.th/wp-content/uploads/2023/12/year-in-review-2023-cover.png" alt="" hieght="100%">
    </div>
  </div>
</body>