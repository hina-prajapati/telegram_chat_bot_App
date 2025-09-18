<footer class="third-bg">
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <div class="footer_top">
                    {{-- <h4> Share Your Favorite Mobile Apps With Your Friends  </h4> --}}

                    <ul>
                            <li> <a target="_blank" href="https://www.facebook.com/MatchEaseIndia"> <i class="fab fa-facebook"
                                        aria-hidden="true"></i> </a>
                            </li>
                            <li> <a target="_blank" href="https://www.instagram.com/match.ease"> <i class="fab fa-instagram"
                                        aria-hidden="true"></i> </a> </li>
                        </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="footer_bottom fourth-bg">
        <!-- Keep Footer Credit Links Intact -->
        <p> @2025 MatchEase. All Rights Reserved. </p>
        <a href="#" class="backtop"> ^ </a>
    </div>

</footer>

<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/interface.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#menu_slide").click(function() {
            $("#navbar").slideToggle('normal');
        });
    });
</script>
<!--Menu Js Right Menu-->
<script type="text/javascript">
    $(document).ready(function() {
        $('#navbar > ul > li:has(ul)').addClass("has-sub");
        $('#navbar > ul > li > a').click(function() {
            var checkElement = $(this).next();
            $('#navbar li').removeClass('dropdown');
            $(this).closest('li').addClass('dropdown');
            if ((checkElement.is('ul')) && (checkElement.is(':visible'))) {
                $(this).closest('li').removeClass('dropdown');
                checkElement.slideUp('normal');
            }
            if ((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
                $('#navbar ul ul:visible').slideUp('normal');
                checkElement.slideDown('normal');
            }
            if (checkElement.is('ul')) {
                return false;
            } else {
                return true;
            }
        });
    }); <
    !--end-- >
</script>
<script type="text/javascript">
    $("#navbar").on("click", function(event) {
        event.stopPropagation();
    });
    $(".dropdown-menu").on("click", function(event) {
        event.stopPropagation();
    });
    $(document).on("click", function(event) {
        $(".dropdown-menu").slideUp('normal');
    });

    $(".navbar-header").on("click", function(event) {
        event.stopPropagation();
    });
    $(document).on("click", function(event) {
        $("#navbar").slideUp('normal');
    });
</script>
