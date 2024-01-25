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
    let selectElement = document.getElementById('rkr_select');
    if (selectElement) {

        if (!selectElement.classList.contains('rkr_disabled')) {
            let first_option = selectElement.querySelector('option');

            if (selectElement.value == 0) {
                first_option.disabled = true;
            } else {
                first_option.disabled = false;
            }

            selectElement.addEventListener('change', (e) => {
                if (e.currentTarget.value == 0) {
                    first_option.disabled = true;
                } else {
                    first_option.disabled = false;
                }
            });
        } else {
            selectElement.disabled = true;
        }
    }

});
