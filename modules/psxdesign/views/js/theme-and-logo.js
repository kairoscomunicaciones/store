/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License version 3.0
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License version 3.0
 */

// WARNING : this JS is only loaded on page Theme & Logo

// waiting for end document loading
$(document).ready(() => {

  // Loader added in notification after submit.
  const psxDesignUpgradeForm = $('#psxdesign-upgrade-form');
  psxDesignUpgradeForm.on('submit',  () => {
    psxDesignUpgradeForm.html('<i class="spinner bg-transparent"></i>')
  })

  // Delete export current theme button
  const exportCurrentThemeButton = $('#page-header-desc-configuration-export')
  if (exportCurrentThemeButton) {
      exportCurrentThemeButton.remove()
  }

  // Save add new theme button for later
  const addNewThemeButton = $('#page-header-desc-configuration-add')

  // create a new theme card
  const themeCardContainer = document.createElement('div')
  themeCardContainer.className = 'col-lg-3 col-md-4 col-sm-6 theme-card-container'
  const themeCard = document.createElement('div')
  themeCard.className = 'card theme-card'
  themeCard.style.backgroundColor = '#f8f8f8'
  themeCard.style.display = 'flex'
  themeCard.style.justifyContent = 'center'
  themeCard.style.alignItems = 'center'
  themeCard.setAttribute('data-role', 'theme-card-container')

  // Change place of new theme button into theme card
  addNewThemeButton.detach()
  themeCard.append(addNewThemeButton[0])
  themeCardContainer.append(themeCard)

  // Add the theme card with add new theme button to the list after last theme card
  const $themeList = $('.card-header[data-role="theme-shop"] ~ .card-body>.row')
  const themeCards = Array.from($themeList.children()).filter((el) =>  el.classList.contains('theme-card-container') && $(el).children()[0].classList.contains('theme-card'))
  const lastThemeCard = themeCards[themeCards.length - 1]

  $(lastThemeCard).after(themeCardContainer)
})


