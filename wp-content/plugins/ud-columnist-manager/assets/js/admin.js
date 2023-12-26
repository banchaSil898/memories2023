
// var columnistBox, array_unique_noempty;
// jQuery(document).ready(function ($) {
//
//     columnistBox = {
//         fakeInputEl: $('.ud_columnistdiv  input.ud-columnist-fake-input'),
//         chosenColumnistEL: $('.ud_columnistdiv .ud_columnist_chosen span'),
//         InputNameEL: $('.ud_columnistdiv input#ud_columnist\\[name\\]'),
//         deleteButton: $('#ud_columnist_del'),
//
//         setValue: function (termId,termName) {
//             this.InputNameEL.val(termName);
//             this.chosenColumnistEL.text(termName);
//             this.deleteButton.show();
//         },
//
//
//         clearValue: function () {
//             this.InputNameEL.val("");
//             this.chosenColumnistEL.text("");
//             this.deleteButton.hide();
//         },
//
//         init: function () {
//             var fakeInputEl = this.fakeInputEl;
//
//             fakeInputEl.keyup(function (e) {
//                 if (13 == e.which) {
//                     return false;
//                 }
//             }).keypress(function (e) {
//                 if (13 == e.which) {
//                     e.preventDefault();
//                     return false;
//                 }
//             }).each(function () {
//                 // var tax = $(this).closest('div.tagsdiv').attr('id');
//                 $(this).autocomplete({
//                     source: function (request, response) {
//                         var data = {
//                             'action': 'ud_search_columnist',
//                             'term': request.term
//                         };
//                         jQuery.get(ajaxurl, data, function (res) {
//                             var obj = JSON.parse(res);
//                             console.log(obj);
//                             var results = [];
//                             for(var key in obj) {
//                                 if (obj.hasOwnProperty(key)) {
//                                     results.push({label:obj[key],value:obj[key]});
//                                 }
//                             }
//                             console.log(results);
//                             response(results);
//                         })
//                     },
//                     delay: 500,
//                     minLength: 2,
//                     select: function (event, ui) {
//                         columnistBox.fakeInputEl.val("");
//                         columnistBox.setValue(ui.item.value, ui.item.label);
//                         console.log(ui);
//                         return false;
//                     },
//                 });
//             });
//
//             this.deleteButton.click(function(e){
//                 e.preventDefault();
//                 columnistBox.clearValue();
//             });
//
//             if (this.chosenColumnistEL.text()){
//                 this.deleteButton.show();
//             }else {
//                 this.deleteButton.hide();
//             }
//         }
//
//     }
//
//     columnistBox.init();
// });

jQuery(document).ready(function ($) {
    var udSelectColumnist = {
        selectDiv: $('.ud-cm-select-columnist'),
        curPostID: 0,
        curPostName: "",
        init: function () {

            var fakeInput = this.selectDiv.find('.fake_input');

            fakeInput.keyup(function (e) {
                if (13 == e.which) {
                    return false;
                }
            }).keypress(function (e) {
                if (13 == e.which) {
                    e.preventDefault();
                    return false;
                }
            }).each(function () {
                $(this).autocomplete({
                    source: function (request, response) {
                        var data = {
                            action: 'ud_cm_search_columnist',
                            search_string: request.term
                        };
                        jQuery.get(ajaxurl, data, function (res) {
                            var obj = JSON.parse(res);
                            var results = [];
                            for (var key in obj) {
                                if (obj.hasOwnProperty(key)) {
                                    results.push({label: obj[key], value: obj[key], item_id:key});
                                }
                            }
                            response(results);
                        })
                    },
                    delay: 200,
                    minLength: 2,
                    select: function (event, ui) {
                        $(this).val("");
                        udSelectColumnist.updateVal($(this).parent().parent(), ui.item.item_id, ui.item.value);

                        return false;
                    }
                });
            });

            $('.ud_columnist_del').click(function(e){
                e.preventDefault();
                var realInput = $(this).parent().parent().find('.real-input');
                var chosenItemDiv = $(this).parent().parent().find('.chosen_item');
                realInput.val('');
                chosenItemDiv.empty();
            });
        },

        updateVal: function(elem, post_id, post_name){
            var realInput = elem.find('.real-input');
            if(post_id === 0){
                realInput.val('');
            }else{
                var chosenItemDiv = elem.find('.chosen_item');
                chosenItemDiv.empty();

                chosenItemDiv.append($('<span>'+post_name+'</span>'));

                realInput.val(post_id);
            }

        },
    };
    udSelectColumnist.init();
});