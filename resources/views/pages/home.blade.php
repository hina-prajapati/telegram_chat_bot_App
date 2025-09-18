@extends('pages.partials.app')
@section('main-content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="{{ asset('css/custome.css') }}">
    <style>
        p {
            /* font-size: 14px !important; */
            color: #585858;
            line-height: 27px;
        }

        h5 {
            font-size: 25px;
        }

        audio,
        canvas,
        progress,
        video {
            display: inline-block;
            vertical-align: baseline;
            border: 4px solid #ed726a;
            border-radius: 5px;
        }

        .features_detail i {
            font-size: 28px;
            color: #ea7067;
        }

        .section_btn .btn.btn-default {
            background: hsl(4.76deg 79.75% 69.02%) none repeat scroll 0 0;
        }

        .section_btn>span .btn.btn-default {
            background: #eb6d65;
        }

        .present span {
            color: hsl(3deg 55.04% 71.39%);
            font-weight: 700;
        }

        .logo_img {
            /* padding: 9px 0; */
            width: 280px;
            margin: 0 auto;
            margin-top: -48px;
        }

        header {
            background: rgb(238 200 198);
            margin: 0 auto;
            padding: 0;
        }

        .navbar-fixed-top {
            top: 0;
            border-width: 0 0 1px;
            height: 75px;
        }

        @media (max-width: 767px) {

            .navbar.navbar-default,
            #navbar.collapse.navbar-collapse {
                background-color: hsl(0deg 0% 0% / 1%);
            }

            /* .logo_img {
                        width: 92%;
                        margin-top: -55px;
                    } */

            .navbar-fixed-top {

                height: 90px;
            }
        }

        @media (max-width: 767px) {
            .present h1 {
                font-size: 40px;
                line-height: 60px;
                padding-bottom: 20px;
            }
        }

        footer .footer_bottom {
            padding: 12px;
            text-align: center;
        }

        .footer_top li {
            background: hsl(216, 100%, 1%) none repeat scroll 0 0;
            border-radius: 100%;
            display: inline-block;
            font-size: 26px;
            margin: 20px 5px;
            width: 50px;
            height: 50px;
            line-height: 48px;
            text-align: center;
        }

        .footer_top {
            margin: -8px 0;
            text-align: center;
        }

        @media (max-width: 767px) {
            .logo_img {
                padding: 9px 0;
                width: 67%;
                /* margin: 0 auto; */
                margin-top: -36px;
            }
        }
    </style>
    <section id="home" class="top_banner_bg secondary-bg">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="top_banner">
                    </div>

                    <div class="col-md-7">
                        <div class="present">
                            <h1>Find Your Life Partner with Ease.</h1>

                            <h5> Introducing <span>MatchEase</span>, your<span> private, secure,</span>and completely <span>
                                    free matrimony platform,</span> right inside Telegram. <b> </b> No more <span>endless
                                    apps</span> or
                                complex websites—just genuine connections, made simple. </h5>

                            <div class="section_btn">
                                <a href="https://t.me/Match_Ease_bot" target="_blank"> <button
                                        class="btn btn-default jjgkfg" type="submit"> Get
                                        Started on Telegram</button> </a>

                                {{-- <span> <a href="#"> <button class="btn btn-default" type="submit"><i class="fa fa-android" aria-hidden="true"></i> Play Store</button> </a> </span>							 --}}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="present_img">
                            <img src="images/START__8_-removebg-preview.png" alt="image">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="primary-bg">
        <div class="container">
            <div class="row">
                <div class="col-md-12">

                    <div class="section_heading">
                        <h2> How It Works</h2>
                        <h4>Getting started with MatchEase is simple and takes just a few steps. Follow the commands below
                            to create your profile, explore matches, and connect securely.</h4>
                    </div>

                    <div class="col-md-6">
                        <div class="features_detail">
                            <ul>
                                <li>

                                    <i class="fab fa-telegram-plane"></i>
                                    <h5> 1. Start the MatchEase Bot</h5>
                                    <p style="font-size: 14px !important;">Open Telegram and search for our bot or click the
                                        button below. Begin by typing
                                        <code>/start</code>.
                                    </p>
                                </li>
                                <li>

                                    <i class="fa fa-user" aria-hidden="true"></i>
                                    <h5>2. Complete Your Profile</h5>
                                    <p style="font-size: 14px !important;">Answer a few questions about yourself and your
                                        preferences. All data is kept private
                                        and secure.</p>
                                </li>

                                <li>

                                    <i class="fa fa-camera" aria-hidden="true"></i>
                                    <h5>3. View Your Profile</h5>
                                    <p style="font-size: 14px !important;">Use <code>/profile</code> anytime to view your
                                        details.</p>
                                </li>

                                <li>

                                    <i class="fa fa-paperclip" aria-hidden="true"></i>
                                    <h5>4. Explore Matches</h5>
                                    <p style="font-size: 14px !important;">Type <code>/matches</code> to see profiles that
                                        match your preferences.</p>

                                </li>


                                <li>

                                    <i class="fa fa-desktop" aria-hidden="true"></i>
                                    <h5>5. Update Your Profile</h5>
                                    <p style="font-size: 14px !important;">Want to make changes? Just use
                                        <code>/update_profile</code>.
                                    </p>
                                </li>

                                <li class="mb-4">
                                    <i class="fa fa-check-circle" aria-hidden="true"></i>
                                    <h5>6. View Approved Profiles</h5>
                                    <p style="font-size: 14px !important;">Use <code>/approved</code> to see profiles you've
                                        shown interest in.</p>
                                </li>

                                <li class="mb-4">
                                    <i class="fa fa-clock" aria-hidden="true"></i>
                                    <h5>7. View Pending Profiles</h5>
                                    <p style="font-size: 14px !important;">Use <code>/pending</code> to check pending
                                        connection requests or matches.</p>
                                </li>

                                <li>
                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                    <h5>8. Delete Your Profile</h5>
                                    <p style="font-size: 14px !important;">Type <code>/delete_profile</code> if you wish to
                                        remove your profile.</p>
                                </li>

                            </ul>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="features_img pull-left">
                            <img src="images/Start (11) (1).png" alt="image">
                        </div>
                        <div class="features_img pull-left">
                            <img src="images/about.png" alt="image">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section id="services" class="padding_bottom_none our_service_bg">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section_heading section_heading_2">
                        <h2>Ready to Begin Your Journey? </h2>

                        <h4> Your future life partner could be just a click away. Join thousands of others who are
                            connecting with ease on MatchEase.</h4>
                        <div class="section_btn">
                            <span> <a href="https://t.me/Match_Ease_bot" target="_blank"> <button class="btn btn-default"
                                        type="submit">Get
                                        Started on Telegram</button>
                                </a> </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="primary-bg">
        <div class="container">
            <div class="row">
                <div class="col-md-12">

                    <div class="section_heading">
                        <h2> Why MatchEase? </h2>

                        <h4> At MatchEase, we believe that finding a life partner should be simple, safe, and personalized.
                        </h4>
                    </div>

                    <div class="col-md-4">
                        <div class="how_it_work_m text-right">
                            <a href="#"> <i class="fa fa-eye" aria-hidden="true"></i> </a>
                            <a href="#">
                                <h5> Smart, Filter-Based Matching </h5>
                            </a>
                            <p> Say goodbye to manually sifting through PDFs or images in social media groups. Our system
                                uses your preferences to automatically match you with relevant profiles, saving you time and
                                effort.</p>
                        </div>

                        <div class="how_it_work_m text-right">
                            <a href="#"> <i class="fa fa-clock-o" aria-hidden="true"></i> </a>
                            <a href="#">
                                <h5> Completely Free </h5>
                            </a>
                            <p>Get access to all features and matches without any subscription fees. Our mission is to make
                                finding your partner accessible to everyone.</p>
                        </div>

                        <div class="how_it_work_m text-right">
                            <a href="#"> <i class="fa fa-clock-o" aria-hidden="true"></i> </a>
                            <a href="#">
                                <h5> Simple & Intuitive </h5>
                            </a>
                            <p>We've designed a user-friendly experience that removes all the complexity, so you can focus
                                on finding your partner.</p>
                        </div>

                    </div>

                    <div class="col-md-4">
                        <div class="workng_img">
                            <video autoplay muted loop playsinline width="100%" height="100%">
                                <source src="{{ asset('images/video.mp4') }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>

                            {{-- <img src="images/banner.jpeg" alt="image"> --}}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="how_it_work_m text-left">
                            <a href="#"><i class="fa fa-star-o" aria-hidden="true"></i> </a>
                            <a href="#">
                                <h5> Private & Secure </h5>
                            </a>
                            <p> Your profile details are not public. Everything happens within the secure and trusted
                                environment of Telegram. </p>
                        </div>

                        <div class="how_it_work_m text-left">
                            <a href="#"> <i class="fa fa-heart-o" aria-hidden="true"></i> </a>
                            <a href="#">
                                <h5> No Extra App </h5>
                            </a>
                            <p> You don’t need to download another app. Use the platform you already love and trust every
                                day. </p>
                        </div>


                        <div class="how_it_work_m text-left">
                            <a href="#"> <i class="fa fa-heart-o" aria-hidden="true"></i> </a>
                            <a href="#">
                                <h5> Genuine Profiles </h5>
                            </a>
                            <p> Our focus is on connecting you with real, verified individuals who are serious about finding
                                a life partner. </p>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </section>


    {{-- <section id="portfolio" class="fifth-bg">
        <div class="container">
            <div class="row">
                <div class="col-md-12">

                    <div class="section_heading">
                        <h2> Our Portfolio </h2>

                        <h4>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum is simply
                            dummy text </h4>
                    </div>

                    <div class="tags">
                        <ul>
                            <li> <a href="#"> Show All </a> </li>
                            <li> <a href="#"> Design </a> </li>
                            <li> <a href="#"> Audio </a> </li>
                            <li> <a href="#"> Image </a> </li>
                            <li> <a href="#"> YouTube </a> </li>
                        </ul>
                    </div>


                    <div class="portfolio_img">
                        <div class="port_img1">
                            <img src="images/portfolio2.png" alt="image">
                        </div>
                    </div>










                </div>
            </div>
        </div>
    </section> --}}


    {{-- <section id="pricing" class="price_table_bg">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section_heading section_heading_2">
                        <h2> Pricing Table </h2>

                        <h4> We ensure quality & support. People love us & we love them. Here goes some simple dummy text.
                        </h4>
                    </div>

                    <div class="col-md-4">
                        <div class="table-1">
                            <div class="discount">
                                <p> Save 10% </p>
                            </div>

                            <h3> Basic </h3>

                            <div class="price_month">
                                <span class="round">
                                    <h3> $19 </h3>
                                    <span>
                                        <p> /Month </p>
                                    </span>
                                </span>

                            </div>

                            <ul>
                                <li> 1 GB Space </li>
                                <li> 10 GB Bandwidth</li>
                                <li> Enhanced Security </li>
                            </ul>

                            <div class="section_sub_btn">
                                <button class="btn btn-default" type="submit"> <i class="fa fa-cart-plus"
                                        aria-hidden="true"></i> Order Now</button>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="table-1">
                            <div class="discount">
                                <p> Save 15% </p>
                            </div>

                            <h3> Pro </h3>

                            <div class="price_month_m">
                                <span class="round">
                                    <h3> $29 </h3>
                                    <span>
                                        <p> /Month </p>
                                    </span>
                                </span>

                            </div>

                            <ul>
                                <li> 5 GB Space </li>
                                <li> 20 GB Bandwidth</li>
                                <li> Enhanced Security </li>
                            </ul>

                            <div class="section_sub_btn">
                                <button class="btn btn-default" type="submit"> <i class="fa fa-cart-plus"
                                        aria-hidden="true"></i> Order Now</button>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="table-1">
                            <div class="discount">
                                <p> Save 12% </p>
                            </div>

                            <h3> Advanced </h3>

                            <div class="price_month">
                                <span class="round">
                                    <h3> $39 </h3>
                                    <span>
                                        <p> /Month </p>
                                    </span>
                                </span>

                            </div>

                            <ul>
                                <li> 7 GB Space </li>
                                <li> 25 GB Bandwidth</li>
                                <li> Enhanced Security </li>
                            </ul>

                            <div class="section_sub_btn">
                                <button class="btn btn-default" type="submit"> <i class="fa fa-cart-plus"
                                        aria-hidden="true"></i> Order Now</button>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </section> --}}


    {{-- <section id="team" class="primary-bg">
        <div class="container">
            <div class="row">
                <div class="col-md-12">

                    <div class="section_heading">
                        <h2> Team Members </h2>

                        <h4> We're the best professionals in this. Here goes some simple dummy text. Lorem Ipsum is simply
                            dummy text </h4>
                    </div>

                    <div class="col-md-3">
                        <div class="member_detail">
                            <div class="member_img">
                                <img src="images/member_1.png" alt="image">
                            </div>
                            <div class="member_name">
                                <h5> John Capone</h5>
                                <p> Web Art Director</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="member_detail">
                            <div class="member_img">
                                <img src="images/member_2.png" alt="image">
                            </div>
                            <div class="member_name">
                                <h5> Marlon Leend</h5>
                                <p> Head Phographer</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="member_detail">
                            <div class="member_img">
                                <img src="images/member_3.png" alt="image">
                            </div>
                            <div class="member_name">
                                <h5> Robert Son</h5>
                                <p> Marketing Director </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="member_detail">
                            <div class="member_img">
                                <img src="images/member_4.png" alt="image">
                            </div>
                            <div class="member_name">
                                <h5> John Capone</h5>
                                <p> Web Art Director</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section> --}}


    {{-- <section id="testimonial" class="testimonial_bg">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section_heading section_heading_2">
                        <h2>Hear from Our Users </h2>

                        <h4> Testimonial Placeholder:</h4>
                    </div>

                    <div class="testimonial_slide">
                        <div class="testi_detail">
                            <div class="testi_img">
                                <img src="images/testi_img1.png" alt="image">

                                <h5> — Preeti S.</h5>
                            </div>

                            <div class="testi-text">
                                <p> "I was tired of all the mainstream apps. MatchEase on Telegram was a refreshing change.
                                    Simple, secure, and to the point. Highly recommended!" </p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section> --}}


    {{-- <section id="blog" class="primary-bg">
        <div class="container">
            <div class="row">
                <div class="col-md-12">

                    <div class="section_heading">
                        <h2> Our Latest Blog</h2>

                        <h4> We share our best ideas in our blog. Dummy text of the printing and typesetting industry. </h4>
                    </div>


                    <div class="col-md-4">
                        <article class="our_blog">
                            <div class="blog_image">
                                <img src="images/blog-img1.png" alt="image">
                            </div>

                            <div class="blog_detail">
                                <div class="category_heading">
                                    <a href="#">
                                        <h6> Marketing </h6>
                                    </a>
                                    <a href="#">
                                        <h5> Lorem Ipsum is simply dummy text of the printing. </h5>
                                    </a>

                                    <ul>
                                        <li> <i class="fa fa-clock-o" aria-hidden="true"></i> 20 March 2016 </li>
                                        <li> <a href="#"> <i class="fa fa-comments-o" aria-hidden="true"></i>
                                                Comments </a> </li>
                                    </ul>

                                    <a href="#" class="read_more">
                                        <p> Read More <i class="fa fa-long-arrow-right" aria-hidden="true"></i> </p>
                                    </a>
                                </div>
                            </div>
                        </article>
                    </div>

                    <div class="col-md-4">
                        <article class="our_blog">
                            <div class="blog_image">
                                <img src="images/blog-img2.png" alt="image">
                            </div>

                            <div class="blog_detail">
                                <div class="category_heading">
                                    <a href="#">
                                        <h6> Marketing </h6>
                                    </a>
                                    <a href="#">
                                        <h5> Lorem Ipsum is simply dummy text of the printing. </h5>
                                    </a>

                                    <ul>
                                        <li> <i class="fa fa-clock-o" aria-hidden="true"></i> 20 March 2016 </li>
                                        <li> <a href="#"> <i class="fa fa-comments-o" aria-hidden="true"></i>
                                                Comments </a> </li>
                                    </ul>

                                    <a href="#" class="read_more">
                                        <p> Read More <i class="fa fa-long-arrow-right" aria-hidden="true"></i> </p>
                                    </a>
                                </div>
                            </div>
                        </article>
                    </div>

                    <div class="col-md-4">
                        <article class="our_blog">
                            <div class="blog_image">
                                <img src="images/blog-img3.png" alt="image">
                            </div>

                            <div class="blog_detail">
                                <div class="category_heading">
                                    <a href="#">
                                        <h6> Marketing </h6>
                                    </a>
                                    <a href="#">
                                        <h5> Lorem Ipsum is simply dummy text of the printing. </h5>
                                    </a>

                                    <ul>
                                        <li> <i class="fa fa-clock-o" aria-hidden="true"></i> 20 March 2016 </li>
                                        <li> <a href="#"> <i class="fa fa-comments-o" aria-hidden="true"></i>
                                                Comments </a> </li>
                                    </ul>

                                    <a href="#" class="read_more">
                                        <p> Read More <i class="fa fa-long-arrow-right" aria-hidden="true"></i> </p>
                                    </a>
                                </div>
                            </div>
                        </article>
                    </div>






                </div>
            </div>
        </div>
    </section> --}}

    {{-- <section id="contact" class="contact_bg">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section_heading section_heading_2">
                        <h2> Contact Us </h2>
                    </div>

                    <div class="col-md-12 text-center">
                        <div class="contact_text">
                            <ul>
                                <li>
                                    <span><i class="fa fa-envelope-o" aria-hidden="true"></i></span>
                                    <h5> contact@matchease.in </h5>
                                </li>
                            </ul>

                        </div>
                    </div>


                </div>
            </div>
        </div>
    </section> --}}



    {{-- <section class="primary-bg">
        <div class="container">
            <div class="row">
                <div class="col-md-12">

                    <div class="col-md-4">
                        <div class="subscribe">
                            <h3> Stay informed with our newsletter</h3>

                            <h6> Subscribe to our email newsletter for useful tips and resources. </h6>

                            <div class="subscribe_form">
                                <form>
                                    <div class="form-group">
                                        <input type="email" class="form-control" id="exampleInputEmail1"
                                            placeholder="Email Address">
                                    </div>
                                </form>

                                <div class="section_sub_btn">
                                    <button class="btn btn-default" type="submit"> Subscribe </button>
                                </div>
                            </div>

                        </div>


                    </div>

                    <div class="col-md-4">
                        <div class="workng_img">
                            <img src="images/contact_img.png" alt="image">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="subscribe">
                            <h3> Download App Now </h3>

                            <h6> Select your device platform and get download started </h6>

                            <div class="section_btn">
                                <button class="btn btn-default" type="submit"> <i class="fa fa-apple"
                                        aria-hidden="true"></i> App Store</button>

                                <span><button class="btn btn-default" type="submit"><i class="fa fa-android"
                                            aria-hidden="true"></i> Play Store</button></span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section> --}}
@endsection
