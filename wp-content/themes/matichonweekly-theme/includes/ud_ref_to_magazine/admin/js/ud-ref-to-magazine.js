var udRefToMagazineBox, array_unique_noempty;
jQuery(document).ready(function ($) {

    udRefToMagazineBox = {
        fakeInputEl: $('.ud-ref-to-magazine-box  input.ud_ref_to_magazine_info-fake-input'),
        chosenMagazineColumnEL: $('.ud-ref-to-magazine-box .ud_ref_to_magazine_info_chosen span'),
        InputNameEL: $('.ud-ref-to-magazine-box input#ud_ref_to_magazine_info_column_name'),
        deleteButton: $('.ud-ref-to-magazine-box #ud_ref_to_magazine_info_del'),
        startDateInputEL: $('.ud-ref-to-magazine-box input#ud_ref_to_magazine_info_start_date'),
        endDateInputEL: $('.ud-ref-to-magazine-box input#ud_ref_to_magazine_info_end_date'),

        setValue: function (termId, termName) {
            this.InputNameEL.val(termName);
            this.chosenMagazineColumnEL.text(termName);
            this.deleteButton.show();
        },


        clearValue: function () {
            this.InputNameEL.val("");
            this.chosenMagazineColumnEL.text("");
            this.deleteButton.hide();
        },

        auto7day: function (dateStr, instance) {
            console.log(dateStr);
            if (!udRefToMagazineBox.validateDateFormat(udRefToMagazineBox.endDateInputEL.val())) {
                console.log(instance);
                var startDate = udRefToMagazineBox.startDateInputEL.datepicker("getDate");
                console.log(startDate);
                var newEndDate = new Date(startDate.getTime() + (7 * 24 * 60 * 60 * 1000) - 1);
                console.log(newEndDate);
                var newEndDateString = newEndDate.getDate() + '/' + (newEndDate.getMonth() + 1) + '/' + newEndDate.getFullYear();
                console.log(newEndDateString);
                udRefToMagazineBox.endDateInputEL.datepicker("setDate", newEndDateString);
            }
        },

        validateDateFormat: function (dateVal) {

            var dateVal = dateVal;

            if (dateVal == null)
                return false;

            var validatePattern = /^(\d{2})\/(\d{2})\/(\d{4})$/;

            dateValues = dateVal.match(validatePattern);

            if (dateValues == null)
                return false;

            var dtYear = dateValues[3];
            dtMonth = dateValues[2];
            dtDay = dateValues[1];

            if (dtMonth < 1 || dtMonth > 12)
                return false;
            else if (dtDay < 1 || dtDay > 31)
                return false;
            else if ((dtMonth == 4 || dtMonth == 6 || dtMonth == 9 || dtMonth == 11) && dtDay == 31)
                return false;
            else if (dtMonth == 2) {
                var isleap = (dtYear % 4 == 0 && (dtYear % 100 != 0 || dtYear % 400 == 0));
                if (dtDay > 29 || (dtDay == 29 && !isleap))
                    return false;
            }

            return true;
        },

        init: function () {
            var datepickerOption = {
                dateFormat: 'dd/mm/yy',
                showButtonPanel: true
            };

            this.endDateInputEL.datepicker(datepickerOption);

            datepickerOption.onSelect = this.auto7day;
            this.startDateInputEL.datepicker(datepickerOption);

            var fakeInputEl = this.fakeInputEl;

            fakeInputEl.keyup(function (e) {
                if (13 == e.which) {
                    return false;
                }
            }).keypress(function (e) {
                if (13 == e.which) {
                    e.preventDefault();
                    return false;
                }
            }).each(function () {
                // var tax = $(this).closest('div.tagsdiv').attr('id');
                $(this).autocomplete({
                    source: function (request, response) {
                        var data = {
                            'action': 'ud_search_magazine_column',
                            'term': request.term
                        };
                        jQuery.get(ajaxurl, data, function (res) {
                            var obj = JSON.parse(res);
                            console.log(obj);
                            var results = [];
                            for (var key in obj) {
                                if (obj.hasOwnProperty(key)) {
                                    results.push({label: obj[key], value: obj[key]});
                                }
                            }
                            console.log(results);
                            response(results);
                        })
                    },
                    delay: 500,
                    minLength: 2,
                    select: function (event, ui) {
                        udRefToMagazineBox.fakeInputEl.val("");
                        udRefToMagazineBox.setValue(ui.item.value, ui.item.label);
                        console.log(ui);
                        return false;
                    },
                });
            });

            this.deleteButton.click(function (e) {
                e.preventDefault();
                udRefToMagazineBox.clearValue();
            });

            if (this.chosenMagazineColumnEL.text()) {
                this.deleteButton.show();
            } else {
                this.deleteButton.hide();
            }
        }

    }

    udRefToMagazineBox.init();
});