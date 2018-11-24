<footer class="footer">
    © 2017 Intake Doptop
</footer>

</div>
</div>

@yield('modals')
<div class="modal fade footerModal" tabindex="-1" role="dialog">
</div>

<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/plugins/bootstrap/js/popper.min.js') }}"></script>
<script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/jquery.slimscroll.js') }}"></script>
<script src="{{ asset('js/waves.js') }}"></script>
<script src="{{ asset('js/sidebarmenu.js') }}"></script>
<script src="{{ asset('js/custom.js') }}"></script>
<script src="{{ asset('js/jasny-bootstrap.js') }}"></script>
<script src="{{ asset('assets/plugins/sticky-kit-master/dist/sticky-kit.min.js') }}"></script>
<script src="{{ asset('assets/plugins/toast-master/js/jquery.toast.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.6/sweetalert2.js"></script>
<script src="{{ asset('js/plugins.js') }}"></script>

<script src="{{ asset('js/main.js') }}"></script>

@yield('scripts')

<script>
    (function ($, window, document, undefined)
    {
        $(function () {
            $(".ajaxTable").ajaxtable();
        });
    })(jQuery, window, document);
</script>

</body>
</html>