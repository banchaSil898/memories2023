export default class TabManager {
    constructor() {
        jQuery(document).ready(function () {
            this.tabContainers = jQuery(".udof-tab-section");

            // this.activeTabIDs = this.getActiveTabIDsFromURL();

            // show all first tab of all sections
            this.tabContainers.filter(function () {
                    return jQuery(this).parents(".udof-tab-section").length === 0;
                })
                .each(function (index, elem) {
                    let currNode = jQuery(elem);
                    do {
                        let tabContent = currNode.find('> .tab-content').first();
                        let tabLabel = tabContent.parent().find('.nav-tab-wrapper #' + tabContent.attr('id') + '-nav');
                        if (tabContent.length) {
                            this.activateTab(tabLabel, tabContent);
                        }

                        currNode = currNode.find('.udof-tab-section').first();
                    } while (currNode.length);

                }.bind(this));

            // click event registration
            this.tabContainers.find('> .nav-tab-wrapper .nav-tab').click(function (event) {
                event.preventDefault();
                let target = jQuery(event.target);
                let tabID = target.attr('href').substring(1);
                let tabContent = target.parent().parent().find('> #' + tabID);
                this.activateTab(target, tabContent);
            }.bind(this));

        }.bind(this));
    }

    // getActiveTabIDsFromURL() {
    //     let activeTabIDs = {};
    //     let datas = window.location.hash.substring(1).split('&');
    //     for (let data of datas) {
    //         let tmp = data.split('=');
    //         if (tmp[0] && tmp[1]) {
    //             activeTabIDs[tmp[0]] = tmp[1];
    //         }
    //     }
    //     return activeTabIDs;
    // }

    // setHashURL() {
    //     let hashStr = '#';
    //     for (let sectionID in this.activeTabIDs) {
    //         if (!this.activeTabIDs.hasOwnProperty(sectionID)) {
    //             continue;
    //         }
    //         hashStr += sectionID + '=' + this.activeTabIDs[sectionID];
    //     }
    //
    //     window.location.hash = hashStr;
    // }

    activateTab(tabLabelElem, tabContentElem) {
        let tabElems = tabContentElem.parent().find('> .nav-tab-wrapper > .nav-tab');
        let tabContents = tabContentElem.parent().find('> .tab-content');
        tabElems.each(function (index, elem) {
            jQuery(elem).removeClass("nav-tab-active");
        });

        tabContents.each(function (index, elem) {
            jQuery(elem).hide();
        });

        tabContentElem.show();
        tabLabelElem.addClass("nav-tab-active");
        //
        // let tabContentID = tabContentElem.attr('id');
        // let tabContainerID = tabContentElem.parent().attr('id');
        //
        // this.activeTabIDs[tabContentID] = tabContainerID;
    }

}