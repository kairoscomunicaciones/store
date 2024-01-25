/**
 *  @author    Rekire <info@rekire.com>
 *  @copyright Rekire
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */


var ready = (callback) => {
    if (document.readyState != "loading") callback();
    else document.addEventListener("DOMContentLoaded", callback);
}
ready(() => {
    let country_select = document.querySelector('.js-customer-form select[name="id_country"]');
    let state_select = document.querySelector('.js-customer-form select[name="id_state"]');
    let ajax_link = document.querySelector('[name="rkr_change_country"]').value;

    if (state_select && ajax_link) {
        country_select.addEventListener("change", (e) => {
            let xhr = new XMLHttpRequest();
            xhr.open("POST", ajax_link, true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.responseType = 'json';
            xhr.onreadystatechange = () => {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    let options = state_select.querySelectorAll('option:not(:first-child)');
                    options.forEach(o => o.remove());
                    if ("states" in xhr.response) {
                        let newStates = xhr.response['states'];
                        newStates.forEach(function (state) {
                                state_select.options.add(new Option(state['name'], state['value']));
                            }
                        );
                    }
                }
            }
            xhr.send("id_country=" + e.currentTarget.value);
        });
    }

});
