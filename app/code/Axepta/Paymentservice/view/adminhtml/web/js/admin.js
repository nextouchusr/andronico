require(
    [
        'jquery',
        'mage/translate',
    ],
    function ($) {
        $(document).ready(function () {
            var axepta_log_enabled = $("select[id*='enable_log']");

            $('#axepta_log_dowload_btn').on('click', function (e) {
                e.preventDefault();
                var date_from = $("input[id*='log_date_range_from']").val();
                var date_to = $("input[id*='log_date_range_to']").val();

                if (date_from == '' || date_to == ''){
                  if (date_from == '' && $("input[id*='log_date_range_from']").next().next()[0] == undefined ){
                    $("input[id*='log_date_range_from']").next().after('<div style="color: red;">Please enter date.</div>');
                  }
                  if (date_to == '' && $("input[id*='log_date_range_to']").next().next()[0] == undefined ){
                    $("input[id*='log_date_range_to']").next().after('<div style="color: red;">Please enter date.</div>');
                  }
                } else {
                  getDownloadRange(date_from, date_to);
                }

            });

            function getDownloadRange(date_from, date_to) {
                if (axepta_log_enabled.find('option').filter(":selected").val() === '1') {
                    var redirect_url = $("input[id*='log_date_url']").val();
                    redirect_url = redirect_url + '?date_from=' + date_from + '&date_to=' + date_to;
                    window.location.replace(redirect_url);
                }
            }

        });

    }
);
