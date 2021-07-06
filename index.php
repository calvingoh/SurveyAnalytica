<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Home</title>
  <?php include "head.php"; ?>

  <body>
    <?php include "header.php"; ?>
    <!--==========================
    Intro Section
    ============================-->
    <section id="intro" class="clearfix">
      <div class="container d-flex h-100">
        <div class="row justify-content-center align-self-center">
          <div class="col-md-6 intro-info order-md-first order-last">
            <h2>Create your own survey form <span>now!</span></h2>
            <div>
              <?php
                if(isset($_SESSION['user_id'])){
                  echo '<a href="newsurvey.php" class="btn-get-started">Create New Survey Now!</a>';
                }
                else{
                  echo '<a href="register.php" class="btn-get-started">Register Now!</a>';
                }
              ?>
            </div>
          </div>

          <div class="col-md-6 intro-img order-md-last order-first">
            <img src="img/intro-img.svg" alt="" class="img-fluid">
          </div>
        </div>

      </div>
    </section><!-- #intro -->
    <main id="main">
      <!--==========================
      Services Section
      ============================-->
      <section id="services" class="section-bg">
        <div class="container">
          <header class="section-header">
            <h3>Services</h3>
          </header>
          <div class="row">
            <div class="col-md-6 col-lg-4 wow bounceInUp" data-wow-duration="1.4s">
              <div class="box">
                <div class="icon" style="background: #fceef3;"><i class="ion-ios-analytics-outline" style="color: #ff689b;"></i></div>
                <h4 class="title">Create Forms</h4>
                <p class="description">Create forms easily with our fully-customizable form builder!</p>
              </div>
            </div>
			
            <div class="col-md-6 col-lg-4 wow bounceInUp" data-wow-duration="1.4s">
              <div class="box">
                <div class="icon" style="background: #fff0da;"><i class="ion-ios-bookmarks-outline" style="color: #e98e06;"></i></div>
                <h4 class="title">Share Your Forms</h4>
                <p class="description">Publish and share your forms around to get responses!</p>
              </div>
            </div>

            <div class="col-md-6 col-lg-4 wow bounceInUp" data-wow-delay="0.1s" data-wow-duration="1.4s">
              <div class="box">
                <div class="icon" style="background: #e6fdfc;"><i class="ion-ios-paper-outline" style="color: #3fcdc7;"></i></div>
                <h4 class="title">Analyze form responses</h4>
                <p class="description">Study your form responses through visual graphics!</p>
              </div>
            </div>
          </div>
        </div>
      </section><!-- #services -->

      <!--==========================
      Why Us Section
      ============================-->
      <!-- <section id="why-us" class="wow fadeIn">
        <div class="container-fluid">

          <header class="section-header">
            <h3>Why choose us?</h3>
			<div class="ht-30">
          </header>

          <div class="row">

            <div class="col-lg-6">
              <div class="why-us-img">
                <img src="img/why-us.jpg" alt="" class="img-fluid">
              </div>
            </div>

            <div class="col-lg-6">
              <div class="why-us-content">
                <p>Survey Analytica helps you to create forms, share them and analyze data from responses all for free.</p>
                <p>
                  Create and customize your forms easily with our survey form builder.
                  Survey Analytica is suitable to use for everyone to create their ideal survey form.
                  Whether you wanna handle events, product feedback or registration forms, Survey Analytica is the best tool for you!
                </p>

                <div class="features wow bounceInUp clearfix">
                  <i class="fa fa-diamond" style="color: #f058dc;"></i>
                  <h4>No membership fees</h4>
                  <p>Survey Analytica is completely free, all you have to do is register an account with us and have unlimited access to all our features.</p>
                </div>

                <div class="features wow bounceInUp clearfix">
                  <i class="fa fa-object-group" style="color: #ffb774;"></i>
                  <h4>Simple and easy to use</h4>
                  <p>Survey Analytica's user friendly interface are suitable for both novice users and power users to create and share their surveys.</p>
                </div>

                <div class="features wow bounceInUp clearfix">
                  <i class="fa fa-bar-chart" style="color: #589af1;"></i>
                  <h4>Data Analysis at your fingertips</h4>
                  <p>Survey forms created within Survey Analytica is powered by data analytics and charts and graphs are created based on the form data.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section> -->

      <!--==========================
      Call To Action Section
      ============================-->
      <section id="call-to-action" class="wow fadeInUp">
        <div class="container">
          <div class="row">
            <div class="col-lg-9 text-center text-lg-left">
              <?php
                if(isset($_SESSION['user_id'])){
                  echo '
                  <h3 class="cta-title">Create your very own survey form</h3>
                  <p class="cta-text"> Create forms easily!</p>
                  </div>
                  <div class="col-lg-3 cta-btn-container text-center">
                    <a class="cta-btn align-middle" href="newsurvey.php">New Survey</a>
                  ';
                }
                else{
                  echo '
                  <h3 class="cta-title">Register now to create your very own form!</h3>
                  <p class="cta-text"> Create forms easily!</p>
                  </div>
                  <div class="col-lg-3 cta-btn-container text-center">
                    <a class="cta-btn align-middle" href="register.php">Register Now</a>
                  ';
                }
              ?>
            </div>
          </div>

        </div>
      </section><!-- #call-to-action -->
	  
		<!--==========================
		Frequently Asked Questions Section
		============================-->
		<section id="faq">
		  <div class="container">
			<header class="section-header">
			  <h3>Frequently Asked Questions</h3>
			</header>

			<ul id="faq-list" class="wow fadeInUp">
			  <li>
				<a data-toggle="collapse" href="#faq1" class="collapsed">What is Survey Lab? <i class="ion-android-remove"></i></a>
				<div id="faq1" class="collapse" data-parent="#faq-list">
				  <p>
          Survey Lab is a survey form builder that allows you to create survey forms for free, provided you have an account with us.
				  </p>
				</div>
			  </li>

			  <li>
				<a data-toggle="collapse" href="#faq2" class="collapsed">Do I have to pay to access more features in Survey Lab? <i class="ion-android-remove"></i></a>
				<div id="faq2" class="collapse" data-parent="#faq-list">
				  <p>
					The answer is no you don't! Survey Lab is completely free, anyone with a registered account with us can access all the form-building features without restrictions.
				  </p>
				</div>
			  </li>

			  <li>
				<a data-toggle="collapse" href="#faq3" class="collapsed">What is so special about Survey Lab? <i class="ion-android-remove"></i></a>
				<div id="faq3" class="collapse" data-parent="#faq-list">
				  <p>
					First of all, Survey Lab is completely free. Secondly, Survey Lab offer data analytics for each survey form you create - survey responses are analyzed and constructed into charts
					and graphs for an easy view of your data!
				  </p>
				</div>
			  </li>

			  <li>
				<a data-toggle="collapse" href="#faq4" class="collapsed">What if I dont want to register for an account? <i class="ion-android-remove"></i></a>
				<div id="faq4" class="collapse" data-parent="#faq-list">
				  <p>
					Non-registered users can provide responses to other people's forms via email or through social media apps.
				  </p>
				</div>
			  </li>

			<li>
			  <a data-toggle="collapse" href="#faq5" class="collapsed">Can I still respond to other surveys if I have an account? <i class="ion-android-remove"></i></a>
			  <div id="faq5" class="collapse" data-parent="#faq-list">
				<p>
				  Yes, registered users can respond to any surveys too anonymously.
				</p>
			  </div>
			</li>
		  </ul>

		  </div>
		</section><!-- #faq -->
		</main>
<?php include "footer.php"; ?>

	</body>
</html>
