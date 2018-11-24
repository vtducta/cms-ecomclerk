;
var NotifyTimer;
(function ($, window, document, undefined) {
    $(function () {

        /*//Global Events for our app
         This is to alert when the user clicks the delete button*/
        $("a.delete").click(function (e) {
            e.stopPropagation();
            var confirms = confirm('Are you sure you want to delete the selected item?');
            if (!confirms) {
                e.preventDefault();
                return false;
            }
        });

        $(".item-arena").on('click', '.item-delete', function(e){
            $(this).closest($(this).attr('data-row')).remove();
            e.preventDefault();
            return false;
        });

        /*//multiple checkbox select */
        $(".checkbox-boss").click(function (e)
        {
            if ($(this).is(':checked')) {
                $(".checkbox-slaves").prop('checked', true).attr('checked', 'checked');
                //due to tbeme error we need to loop through every slaves and change its parent span class
                //This really sucks :(
                $(".checkbox-slaves").each(function (i, v) {
                    $(this).parent('span').addClass('checked')
                });
            }
            else {
                $(".checkbox-slaves").prop('checked', false).removeAttr('checked');
                //due to tbeme error we need to loop through every slaves and change its parent span class
                //This really sucks :(
                $(".checkbox-slaves").each(function (i, v) {
                    $(this).parent('span').removeClass('checked')
                });
            }
        });


        $(".deleteArena").on('click', 'a.ajaxdelete', function (e) {
            e.stopPropagation();
            var $request = new RequestManager.Ajax();
            var confirms;
            if ($(this).attr('data-confirm')) {
                confirms = confirm($(this).attr('data-confirm'));
            }
            else {
                confirms = confirm('Are you sure you want to delete the selected item ?');
            }

            if (!confirms) {
                e.preventDefault();
                return false;

            }
            var config = {
                url: $(this).attr('data-url'),
                data: {'_delete_token': $(this).attr('data-token')},
                type: 'GET'
            };
            var This = $(this);


            $request.Html(config).done(function (response) {
                if (response == '1') {
                    //alert(data);
                    if (This.data('prev')) {
                        /* console.log('should delete previous');
                         console.log(This.closest('.deleteBox').prev('.deleteBox'));*/
                        This.closest('.deleteBox').prev('.deleteBox').addClass('deleting').hide(2000);
                    }
                    This.closest('.deleteBox').addClass('deleting').hide(2000);
                }
                else {
                    alert('Delete Failed');
                }
                //Loader.finish();
            }).fail(function (jqXHR, textStatus, errorThrown) {
                console.log(textStatus);
                console.log(errorThrown)
            });

            e.preventDefault();
            /* e.stopPropagation();*/
        });

        $(document).on('click', '.modalFetcher', function (e) {
            var modal = $($(this).attr('data-target'));
            var data = $(this).data();
            $.get($(this).attr('href'), data, function (response) {
                modal.empty().append(response);
                modal.modal({show:true});
            });
            e.preventDefault();
            return false;
        });

        $(document).on('submit', '.bulk-action-form', function (e) {
            if ($(this).find('.bulk-items:checked').length == 0) {
                alert("Please select at least one item");
                e.preventDefault();
                return false;
            }
        });

        $(document).on('click', '.removeRow', function (e) {
            $(this).closest($(this).attr('data-target')).remove();
            e.preventDefault();
            return false;
        });


        //ajax form submit
        $(document).on('submit', '.ajaxForm', function (e) {
            var $request = new RequestManager.Ajax();
            Loader.init();
            var submitBtn = $(this).find('[type="submit"]');
            submitBtn.addClass('disabled');
            var config = {
                url: $(this).attr('action'),
                data: new FormData($(this)[0]),
                method: $(this).attr('method'),
                processData: false,
                contentType: false
            };
            var This = $(this);
            $request.post(config).done(function (response) {
                Loader.set(80);
                $request.processResponse(response, This);
                Loader.finish();
                submitBtn.removeClass('disabled');

            }).fail(function (jqXHR, textStatus, errorThrown) {
                console.log(textStatus);
                console.log(errorThrown);
                submitBtn.removeClass('disabled');
            });
            e.preventDefault();
            return false;
        });


        $(document).on('submit', '.simpleAjaxForm', function (e) {
            var $request = new RequestManager.Ajax();
            Loader.init();
            var submitBtn = $(this).find('.submit-btn');
            /*submitBtn.addClass('disabled');*/
            var config = {
                url: $(this).attr('action'),
                data: new FormData($(this)[0]),
                dataType: 'html',
                /*method: $(this).attr('method'),*/
                processData: false,
                contentType: false
            };

            var This = $(this);
            $request.post(config).done(function (response) {
                /*  Loader.set(80);*/
                console.log(response);
                //$request.processResponse(response, This);
                Loader.finish();
                /*submitBtn.removeClass('disabled');*/

            }).fail(function (jqXHR, textStatus, errorThrown) {
                console.log(textStatus);
                console.log(errorThrown)
            });
            e.preventDefault();
            return false;
        });


        $(document).on('change', '.actionOnChange', function (e) {
            var $request = new RequestManager.Ajax();
            var config = {
                url: $(this).attr('data-url'),
                data: {
                    _value: $(this).val()
                }
            };
            var This = $(this);
            $request.get(config).done(function (response) {
                /*console.log(response);*/
                $(This.attr('data-change')).val(response.data);
            }).fail(function (jqXHR, textStatus, errorThrown) {
                console.log(textStatus);
                console.log(errorThrown)
            });
        });


        /*low lets update the lazy selectors*/
        $(".lazySelector").each(function (i, v) {
            var selectedVal = $(this).attr('data-selected');
            console.log(selectedVal);
            $(this).find('option').each(function (i, v) {
                if ($(this).val() == selectedVal) {
                    $(this).attr('selected', 'selected')
                }
                return true;
            })
        });
        $("div").on('click', '.close', function (e) {
            $(this).closest('.panel-body').remove();
        });

    });


})(jQuery, Window, document);