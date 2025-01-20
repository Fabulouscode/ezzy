@extends('frontlayout.master')

@section('content')
<div id="home" class="main-banner banner-style-two banner-bg-two">
    <div class="d-table">
        <div class="d-table-cell">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-8 col-xl-7">
                        <div class="banner-text">
                            <h1>Find thousands of healthcare providers near you</h1>
                            <p>Ezzycare Connects you to nearby healthcare providers for urgent or non urgent care.  Find Doctors in any specialty,  do Laboratory tests from your home and get medicines delivered to your address.</p>
                            {{-- <div class="banner-btn">
                                <a href="#apps" class="download-appbtn">Download App</a>
                            </div> --}}
                        </div>
                        <div class="why-choose-btn">
                            <a target="_blank" href="https://play.google.com/store/apps/details?id=com.ezzycare.app" >
                                <i class="flaticon-play-store"></i>
                                <p>Download on</p>
                                <h5>Google Play</h5>
                            </a>
                            <a target="_blank" href="https://apps.apple.com/tt/app/ezzycare/id1566288649">
                                <i class="flaticon-app-store"></i>
                                <p>Download on</p>
                                <h5>App Store</h5>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-4 col-xl-5">
                        <div class="banner-img text-right">
                            <img src="{{ asset('frontend/img/home-mockup.png') }}" class="img-fluid" alt="iphone" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- <div class="banner-shape">
        <img src="{{ asset('frontend/img/shape/home-shape.png') }}" alt="shape" />
        <img src="{{ asset('frontend/img/shape/home-shape-two.png') }}" alt="shape" />
        <img src="{{ asset('frontend/img/shape/home-shape-four.png') }}" alt="shape" />
    </div> -->
</div>

<section id="aboutus" class="why-choose pt-100 pb-100">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-4">
                <div class="why-choose-img wow fadeInUp" data-wow-duration="1s">
                    <img src="{{ asset('frontend/img/app-landing/2.png') }}" alt="why choose image" />
                </div>
            </div>
            <div class="col-lg-7 offset-lg-1">
                <div class="why-choose-text wow fadeInUp" data-wow-duration="2s">
                    <div class="section-title">
                        <span>About Us</span>
                        <h2>Welcome to Ezzycare</h2>
                        <p>Ezzycare is a novel medical solution to the challenges of both healthcare providers and healthcare seekers. It connects health care seekers to the closest providers in their vicinity using their geo location similar to how Uber cab connects drivers to riders.</p>
                        <p>Healthcare seekers pay providers via this platform. It solves the ever present challenge of getting service in an easy and convenient way.</p>
                        <p>Health care providers decide their charges for various services.</p>
                        <p>Connect with the best doctors in  less than 20 seconds via geo location. 100% safe and secure online medical consultation.</p>
                    </div>
                    <div class="media">
                        <i><img src="{{ asset('frontend/img/ic_healthcare.png') }}" class="img-fluid" alt=""></i>
                        <div class="media-body">
                            <h3 class="mt-0">Healthcare Provider</h3>
                            Urgent and non-urgent consultations with Doctors of various specialties via video calls. Urgent and non-urgent home and clinic visits between Doctors and care seekers. Home visit by nearby nurses, Physiotherapists and massage therapists at the convenience of care seekers.
                        </div>
                    </div>
                    <div class="media">
                        <i><img src="{{ asset('frontend/img/ic_medicine.png') }}" class="img-fluid" alt=""></i>
                        <div class="media-body">
                            <h3 class="mt-0">Medicine/Pharmacy</h3>
                            Drug orders from pharmacists and home delivery of drugs. Tracking of drug orders made to pharmacists in real time. Drugs can be delivered at your door step with Ezzycare pharmacy.
                        </div>
                    </div>
                    <div class="media">
                        <i><img src="{{ asset('frontend/img/ic_laboratories.png') }}" class="img-fluid" alt=""></i>
                        <div class="media-body">
                            <h3 class="mt-0">Laboratory</h3>
                            Care seekers can request Laboratory tests from anywhere and nearby scientists and pathologists can visit to carry out tests. Care seekers can order various radiological tests like scans and get nearby radiologists and radiographers to do the tests. Home sample collection with utmost safety and neccessary precaution for diagnostic tests when you request lab services from Ezzycare.
                        </div>
                    </div>
                    <!-- <div class="media">
                        <i class="flaticon-wallet mr-3"></i>
                        <div class="media-body">
                            <h3 class="mt-0">Online Payment</h3>
                            We accept online payment. Manage your trip without the hassle of buying the ticket in person or paying for it in cash. Just transfer the money online.
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</section>

<section id="featuresss" class="feature-section bg_grey pt-100 pb-100 d-none">
    <div class="container">
        <div class="section-title">
            <span>App Features</span>
            <h2>All in 3 Steps</h2>
            <p>Find and Hire miner near you to get best mining profit with us . Your earning is just a click aways. Create or Join Association on your profitable ratio instantly.</p>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-6 col-sm-6 mb-4">
                <div class="feature-card wow fadeInUp" data-wow-duration="2s">
                    <i class="flaticon-layers"></i>
                    <h3>Network of 1000+ Doctors</h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Recusandae delectus cupiditate rem distinctio</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6 mb-4">
                <div class="feature-card active wow fadeInUp" data-wow-duration="2s">
                    <i class="flaticon-clipboard"></i>
                    <h3>Find Professionals Based on</h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Recusandae delectus cupiditate rem distinctio</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6 mb-4 offset-sm-3 offset-md-3 offset-lg-0">
                <div class="feature-card wow fadeInUp" data-wow-duration="2s">
                    <i class="flaticon-credit-card"></i>
                    <h3>Discuss with The Experts.</h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Recusandae delectus cupiditate rem distinctio</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="features" class="how-use pt-100 pb-100 bg_grey">
    <div class="container">
        <div class="section-title">
            <span>App Features</span>
            <h2>All in 3 Steps</h2>
            <!-- <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Suscipit perspiciatis aliquam deserunt libero necessitatibus nesciunt repudiandae pariatur officiis quis nemo</p> -->
        </div>
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="row align-items-center">
                    <div class="col-lg-6 col-md-6 col-sm-6 wow fadeInUp" data-wow-duration="1s">
                        <div class="how-use-card how-card-one">
                            <span>1</span>
                            <i class="fas fa-mobile-alt"></i>
                            <h3>Download from Play Store/App Store</h3>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 wow fadeInUp" data-wow-duration="1s">
                        <!-- <p>Doctors can register and fill his profile and location. So patient can find them and get online appointment. </p> -->
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 wow fadeInUp" data-wow-duration="1s">
                        <!-- <p class="text-lg-right">Specialities & Location </p> -->
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 wow fadeInUp" data-wow-duration="1s">
                        <div class="how-use-card how-card-two">
                            <span>2</span>
                            <i class="fas fa-users"></i>
                            <h3>Signup</h3>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 wow fadeInUp" data-wow-duration="1s">
                        <div class="how-use-card how-card-three">
                            <span>3</span>
                            <i class="fas fa-search-location"></i>
                            <h3>Find Healthcare Providers</h3>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 wow fadeInUp" data-wow-duration="1s">
                        <!-- <p>Communicate via text, voice, or video call.</p> -->
                    </div>
                </div>
            </div>
            <div class="col-lg-4 offset-lg-1 offset-xl-2 wow fadeInUp" data-wow-duration="1s">
                <div class="how-use-slider owl-carousel owl-theme">
                    <div class="how-use-img">
                        <img src="{{ asset('frontend/img/app-landing/8.png') }}" alt="iphone" />
                    </div>
                    <div class="how-use-img">
                        <img src="{{ asset('frontend/img/app-landing/9.png') }}" alt="iphone" />
                    </div>
                    <div class="how-use-img">
                        <img src="{{ asset('frontend/img/app-landing/10.png') }}" alt="iphone" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div id="video" class="video-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 text-block animate" data-show="fade-in-right" data-vertical_center="true">
                <div class="video-consultation-left">
                    <h2 class="heading mb-3">Voice/Video Consultation</h2>
                    <p>Patient can contact and talk with Doctors within Nigeria. Share your current questions and thoughts with your desired professionals who are connected with you. Affordable voice and video calls make it easy to stay connected with your professionals at all times.</p>
                    <p>Patients can get prescription within app and can order drugs from the application. The drugs can also be delivered at the patients door step.</p>
                </div>
            </div>
            <div class="col-md-6 img-block animate" data-show="fade-in-left">
                <img src="http://www.impiloyami.com/assets/img/mockups/wakeupp-mockup-3.png" alt="" data-uhd class="responsive-on-sm" data-max_width="577" />
            </div>
        </div>
    </div>
</div>

<section id="screenshots" class="screenshots-section pt-100">
    <div class="container">
        <div class="section-title">
            <span>App Journey </span>
            <h2>Take a look at Our App Interface</h2>
            <p>Grab a look at our outstanding and stunning Ezzycare App Interfaces which is easy to use and very easily manageable.</p>
        </div>
        <div class="screenshot-slider owl-carousel owl-theme">
            @for($i = 1; $i <= 10; $i++)
                <div class="screenshoot-img wow fadeInUp" data-wow-duration="1s">
                    <img src="{{ asset('frontend/img/app-landing/app-landing-new/'.$i.'.jpeg') }}" alt="app screenshot" />
                </div>
            @endfor
            {{-- <div class="screenshoot-img wow fadeInUp" data-wow-duration="1s">
                <img src="{{ asset('frontend/img/app-landing/3.png') }}" alt="app screenshot" />
            </div>
            <div class="screenshoot-img wow fadeInUp" data-wow-duration="1s">
                <img src="{{ asset('frontend/img/app-landing/6.png') }}" alt="app screenshot" />
            </div>
            
            <div class="screenshoot-img wow fadeInUp" data-wow-duration="1s">
                <img src="{{ asset('frontend/img/app-landing/4.png') }}" alt="app screenshot" />
            </div>
            <div class="screenshoot-img wow fadeInUp" data-wow-duration="1s">
                <img src="{{ asset('frontend/img/app-landing/5.png') }}" alt="app screenshot" />
            </div>
            <div class="screenshoot-img wow fadeInUp" data-wow-duration="1s">
                <img src="{{ asset('frontend/img/app-landing/7.png') }}" alt="app screenshot" />
            </div>
            <div class="screenshoot-img wow fadeInUp" data-wow-duration="1s">
                <img src="{{ asset('frontend/img/app-landing/11.png') }}" alt="app screenshot" />
            </div>
            <div class="screenshoot-img wow fadeInUp" data-wow-duration="1s">
                <img src="{{ asset('frontend/img/app-landing/12.png') }}" alt="app screenshot" />
            </div> --}}
            
            <!-- <div class="screenshoot-img wow fadeInUp" data-wow-duration="1s">
                <img src="{{ asset('frontend/img/app-landing/1.png') }}" alt="app screenshot" />
            </div>
            <div class="screenshoot-img wow fadeInUp" data-wow-duration="1s">
                <img src="{{ asset('frontend/img/app-landing/1.png') }}" alt="app screenshot" />
            </div>
            <div class="screenshoot-img wow fadeInUp" data-wow-duration="1s">
                <img src="{{ asset('frontend/img/app-landing/1.png') }}" alt="app screenshot" />
            </div>
            <div class="screenshoot-img wow fadeInUp" data-wow-duration="1s">
                <img src="{{ asset('frontend/img/app-landing/1.png') }}" alt="app screenshot" />
            </div> -->
        </div>
        
    </div>
</section>

<section id="apps" class="our-apps pt-5 mt-lg-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-5">
                <div class="our-apps-img wow fadeInUp" data-wow-duration="1s">
                    <img src="{{ asset('frontend/img/home-mockup.png') }}" alt="iphone" />
                </div>
            </div>
            <div class="col-lg-7">
                <div class="our-apps-text">
                    <div class="section-title text-left">
                        <span>Our Mobile Apps</span>
                        <h2>Available on Play Store and App Store</h2>
                    </div>
                    <p>Ezzycare is available on both Android and IOS platforms.</p>
                    <!-- <p>If you are on the phone, you have a phone in your home or place of work, so that you can be used by phone. Ezzycare is available on your Android and IOS phone. Get Installation very easily from below links.</p> -->
                    <div class="why-choose-btn">
                        <a target="_blank" href="https://play.google.com/store/apps/details?id=com.ezzycare.app" >
                            <i class="flaticon-play-store"></i>
                            <p>Download on</p>
                            <h5>Google Play</h5>
                        </a>
                        <a target="_blank" href="https://apps.apple.com/tt/app/ezzycare/id1566288649">
                            <i class="flaticon-app-store"></i>
                            <p>Download on</p>
                            <h5>App Store</h5>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- <div class="apps-shape">
        <img src="{{ asset('frontend/img/shape/1.png') }}" alt="shape" />
        <img src="{{ asset('frontend/img/shape/3.png') }}" alt="shape" />
        <img src="{{ asset('frontend/img/shape/5.png') }}" alt="shape" />
        <img src="{{ asset('frontend/img/shape/6.png') }}" alt="shape" />
        <img src="{{ asset('frontend/img/map-two.png') }}" alt="shape" />
    </div> -->
</section>

<div id="contactus">
    <div class="newsletter pt-100 pb-100">
        <div class="container">
            <div class="section-title">
                <span>Contact Us</span>
                <h2>Get In Touch</h2>
                <p>If you have any questions, just fill in the contact form, and we will answer you shortly.</p>
            </div>
            <div class="row flex-lg-row-reverse">
                <div class="col-xl-7 col-lg-7">
                    <form id="contactForm" name="contactForm" action="javascript:void(0)" class="contact-form">
                        <div class="row"> 
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <div class="form-group">
                                    <input type="text" id="name" name="name" class="form-control" placeholder="Name">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <div class="form-group">
                                    <input type="email" name="email" class="email form-control" id="email" placeholder="Email">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <div class="form-group">
                                    <select name="country" id="msg_country" class="form-control">
                                        @foreach ($country as $key => $item)
                                            @php
                                                $selected = $item == 'Nigeria' ? 'selected' : '';  
                                            @endphp
                                            <option value={{$key}} {{$selected}}>{{$item}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <div class="form-group">
                                    <input type="text" name="mobile" id="msg_mobile" class="form-control" placeholder="Mobile Number">
                                </div>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12">
                                <div class="form-group">
                                    <input type="text" name="subject" id="msg_subject" class="form-control" placeholder="Subject">
                                </div>
                            </div>                                    
                            <div class="col-xl-12 col-lg-12 col-md-12">
                                <div class="form-group">
                                    <textarea id="message"  name="message" rows="5" placeholder="Message" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12">
                                <button type="submit" id="submit" class="contact-btn">Send Message</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-xl-5 col-lg-5">
                    <div class="office-city mt-4 mt-lg-0">
                        <h4>Contact Info</h4>
                        <div class="contact-icon">
                            <div class="single-icon">
                                <i class="fa fa-phone-alt"></i>
                                <p>
                                    <a href="tel:+09154571363">Call : 0915 457 1363</a>
                                </p>
                            </div>
                        </div>
                        <div class="contact-icon">
                            <div class="single-icon">
                                <i class="fa fa-envelope"></i>
                                <p>
                                    <a href="mailto:support@ezzycare.com">Email : support@ezzycare.com</a>
                                </p>
                            </div>
                        </div>
                        <div class="contact-icon">
                            <div class="single-icon">
                                <i class="fa fa-map-marker-alt"></i>
                                <p>plot 448 Reuben Okoya Crescent, Wuye, Abuja, Nigeria</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

       