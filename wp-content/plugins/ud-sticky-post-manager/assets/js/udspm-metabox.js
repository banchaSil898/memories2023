jQuery(document).ready((function(t){var e={loopIDsDiv:t(".udspm-loop-ids"),autoCompleteArgs:{source:function(t,e){var n={action:"ud_sticky_post_search_category",ud_string:t.term};jQuery.post(ajaxurl,n,(function(t){var n=JSON.parse(t),i=[];for(var u in n)n.hasOwnProperty(u)&&i.push({label:n[u],value:n[u],item_id:u});e(i)}))},delay:200,minLength:2,select:function(n,i){return t(this).val(""),e.updateVal(t(this).parent(),i.item.item_id,i.item.value),!1}},init:function(){this.loopIDsDiv.find(".fake_input").autocomplete(this.autoCompleteArgs),this.loopIDsDiv.on("keyup",".fake_input",(function(t){if(13==t.which)return!1})).on("keyup",".fake_input",(function(t){if(13==t.which)return t.preventDefault(),!1})).on("click",".add-button",function(t){var e=jQuery(t.target).parent().find(".udspm-select-loop-id").last(),n=e.clone();n.insertAfter(e).find("input").val(""),n.find(".del-button").remove(),n.find(".fake_input").after('<button type="button" class="del-button button button-small">Delete</button>').autocomplete(this.autoCompleteArgs)}.bind(this)).on("click",".del-button",(function(t){jQuery(t.target).parent().remove()}))},updateVal:function(t,e,n){var i=t.find(".real-input");0===e?i.val(""):i.val(e)}};e.init();var n={selectPostDiv:t(".udspm-select-post"),curPostID:0,curPostName:"",init:function(){this.selectPostDiv.find(".fake_input").keyup((function(t){if(13==t.which)return!1})).keypress((function(t){if(13==t.which)return t.preventDefault(),!1})).each((function(){t(this).autocomplete({source:function(t,e){var n={action:"ud_sticky_post_search_post",ud_string:t.term};jQuery.post(ajaxurl,n,(function(t){var n=JSON.parse(t),i=[];for(var u in n)n.hasOwnProperty(u)&&i.push({label:n[u],value:n[u],item_id:u});e(i)}))},delay:200,minLength:2,select:function(e,i){return t(this).val(""),n.updateVal(t(this).parent(),i.item.item_id,i.item.value),!1}})}))},updateVal:function(e,n,i){var u=e.find(".real-input");if(0===n)u.val("");else{var a=e.find(".chosen_item");a.empty(),a.append(t("<span>"+i+"</span>")),u.val(n)}}};n.init()}));