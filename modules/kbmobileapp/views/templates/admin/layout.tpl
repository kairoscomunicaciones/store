
	{*<link rel="stylesheet" href="http://139.59.46.134/mab/nov/scroll/jquery.mCustomScrollbar.css">
	<script src="http://139.59.46.134/mab/nov/scroll/jquery.mCustomScrollbar.concat.min.js"></script>
*}
<div id="layout_add_edit_form" class="layout_add_edit_form" style="display:none">
    {*<button  onclick="showConfigurationForm()">Cancel</button>*}
    
    <button type="button" class="btn btn-default btn-block" onclick="showConfigurationForm()" style="float:right; padding:3px 5px; width:12%; ">{l s='Cancel' mod='kbmobileapp'}</button>
<div class='row'>
            <div class="productTabs col-lg-3 col-md-3 col-sm-6">
                <div class="list-group">
                    <a id="top_category" class="list-group-item"> {l s='Top Categories' mod='kbmobileapp'}<i class="icon-plus trash" style="padding-right:5px"></i></a>
                    {*start:changes made by aayushi on 3rd January 2020 to add custom banners*}
                    <a id="banner_custom" class="list-group-item">{l s='Banner-Custom' mod='kbmobileapp'}<i class="icon-plus trash" style="padding-right:5px"></i></a>
                    {*end:changes made by aayushi on 3rd January 2020 to add custom banners*}
                    <a id="banner_square" class="list-group-item">{l s='Banner-Square' mod='kbmobileapp'}<i class="icon-plus trash" style="padding-right:5px"></i></a>
                    <a id="banner_HS" class="list-group-item">{l s='Banner-Horizontal Sliding' mod='kbmobileapp'} <i class="icon-plus trash" style="padding-right:5px"></i></a>
                    <a id="banner_grid" class="list-group-item">{l s='Banner-Grid' mod='kbmobileapp'} <i class="icon-plus trash" style="padding-right:5px"></i></a>
                    <a id="banner_countdown" class="list-group-item">{l s='Banner With Countdown Timer' mod='kbmobileapp'} <i class="icon-plus trash" style="padding-right:5px"></i></a>
                    <a id="product_square" class="list-group-item">{l s='Products-Square' mod='kbmobileapp'} <i class="icon-plus trash" style="padding-right:5px"></i></a>
                    <a id="product_HS" class="list-group-item">{l s='Products-Horizontal Sliding ' mod='kbmobileapp'}<i class="icon-plus trash" style="padding-right:5px"></i></a>
                    <a id="product_grid" class="list-group-item"> {l s='Products-Grid' mod='kbmobileapp'}<i class="icon-plus trash" style="padding-right:5px"></i></a>
                    <a id="product_LA" class="list-group-item">{l s='Products Recently access' mod='kbmobileapp'} <i class="icon-plus trash" style="padding-right:5px"></i></a>
					
                </div>
            </div>
			
			
			
            
<!--BOC
	AUTHOR: MONIKA
	Date: 01122018-->

        

		<div class="col-lg-5 col-md-4 col-sm-6">
			<div class="panel panel-default" style="min-height:400px;">
				<ul class="slides">
					
				</ul>
			</div>			
		</div>
		<div class="col-lg-4 col-md-5 col-sm-12">
			<div class="front_preview" >
				<div class="layout_gallery">
                                        {*Start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*}
                                        <div class="topHeader" style="background:{$top_bar_color};">
						<div class="leftmenu">
							<span class="toggleMenu"><i class="icon-bars"></i></span>
						</div>
						<div class="logo">
                                                    {if $display_logo_on_title_bar eq 1}
                                                        <img src="{$title_bar_logo_url}"/>
                                                    {else}
                                                        <p>{l s='Home' mod='kbmobileapp'}</p> 
                                                    {/if}    
						</div>
						<div class="cartSection">
							<span class="cartIcon"><i class="icon-shopping-cart"></i></span>
						</div>
						<div class="searchBar">
							<span class="searchicon"><i class="icon-search"></i></span>
						</div>	
							
					</div>
                                        {*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*}
					<div class="iframe_html" >
						
					</div>
				</div>
			</div>			
		</div>
		<!--Dynamic HTML structure-->
		<div class="top_category" style="display:none;">
			<li class="slide" id="component_position">
				<span class="slideTitle">{l s='Top Categories' mod='kbmobileapp'}</span>
				<!--span class="settings" onclick="settingFunction(this)"><i class="fa fa-gear"></i></span-->
				<span class="edit_component" id="top_category_edit_component" onclick="editTopCategoryComponentFunction(this)"><i class='icon-pencil' style='padding-right:5px'></i></span>
				<span class="trash" id="top_category_delete_component" onclick="trashTopcategoryComponentFunction(this)"><i class='icon-trash' style='padding-right:5px'></i></span>
				<div class="banner_preview layout_div" >
                                    {*Start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*}
{*					<img class="banner_preview_image" src="{$top_category}"/>*}{*variable contains HTML content, Can not escape this*}
                                        <div class="topCategories">
                                        <ul>
                                                <li>
                                                    <span class="catSection">
                                                        <img id="top_category_1" src="{$preview_img_dir}cat1.jpg"/>
                                                        <p id="top_category_text_1">{l s='Category1' mod='kbmobileapp'}</p>
                                                    </span>    
                                                </li>
                                                <li>
                                                    <span class="catSection">
                                                        <img id="top_category_2" src="{$preview_img_dir}cat2.jpg"/>
                                                        <p id="top_category_text_2">{l s='Category2' mod='kbmobileapp'}</p>
                                                    </span>    
                                                </li>
                                                <li>
                                                    <span class="catSection">
                                                        <img id="top_category_3" src="{$preview_img_dir}cat3.jpg"/>
                                                        <p id="top_category_text_3">{l s='Category3' mod='kbmobileapp'}</p>
                                                    </span>     
                                                </li>
                                                <li>
                                                    <span class="catSection">
                                                        <img id="top_category_4" src="{$preview_img_dir}cat4.jpg"/>
                                                        <p id="top_category_text_4">{l s='Category4' mod='kbmobileapp'}</p>
                                                    </span>    
                                                </li>
                                        </ul>
				</div>
				</div>
                                {*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*}
			</li>
		</div>
                {*start:changes made by aayushi on 3rd January 2020 to add custom banners*}
                <div class="banner-custom" id="banner_custom_id" style="display:none;">
			<li class="slide" id="component_position">
				<span class="slideTitle">{l s='Banner Custom' mod='kbmobileapp'}</span>
                                <span class="edit_component" id="banner_custom_edit_component" onclick="editBannerCustomComponentFunction(this)"><i class='icon-pencil' style='padding-right:5px'></i></span>
				<span class="trash" id="banner_custom_delete_component" onclick="trashBannerCustomComponentFunction(this)"><i class='icon-trash' style='padding-right:5px'></i></span>
				<div class="banner_preview layout_div" >
{*					<img class="banner_preview_image" src="{$banner_custom}"/>*}{*variable contains HTML content, Can not escape this*}
                                    <div class="bannerSquare">
                                    <p class="comp_heading">{l s='Banner Custom' mod='kbmobileapp'}</p>
                                    <div class="bannerSquareList">
                                        <span class="BSSection">
                                            <img class="bannerSquareBannerimg" src="{$preview_img_dir}square_banner.jpg"/>
                                            
                                        </span>    
				</div>
                                    
                                </div>
                                </div>
			</li>
		</div>
                {*end:changes made by aayushi on 3rd January 2020 to add custom banners*}
		<div class="banner-slide" id="banner_slider_id" style="display:none;">
			<li class="slide" id="component_position">
				<span class="slideTitle">{l s='Banner-Square' mod='kbmobileapp'}</span>
                                <span class="edit_component" id="banner_square_edit_component" onclick="editBannerSquareComponentFunction(this)"><i class='icon-pencil' style='padding-right:5px'></i></span>
				<span class="trash" id="banner_square_delete_component" onclick="trashBannerSquareComponentFunction(this)"><i class='icon-trash' style='padding-right:5px'></i></span>
				<div class="banner_preview layout_div" >
                                {*<img class="banner_preview_image" src="{$banner_square}"/>*}{*variable contains HTML content, Can not escape this*}
                                {*Start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*}
                                <div class="bannerSquare">
                                    <p id="bannerSquare_comp_heading" class="comp_heading">{l s='Banner Square' mod='kbmobileapp'}</p>
                                    <div class="bannerSquareList" id="bannerSList">
                                        <span class="BSSection">
                                            <img id="bannerSquareBannerimg" class="bannerSquareBannerimg" src="{$preview_img_dir}square_banner.jpg"/>
                                            <p id="bannerSquareBanner_elem_heading" class="elem_heading">{l s='Banner Square' mod='kbmobileapp'}</p>
                                        </span>    
				</div>
                                    
                                </div>
                                {*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*}
				</div>
			</li>
		</div>
		<div class="Hbanner-slide" id="banner_horizontal_id" style="display:none;">
			<li class="slide" id="component_position">
				<span class="slideTitle">{l s='Banner-Horizontal Sliding' mod='kbmobileapp'}</span>
                                {*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*}
                                <span class="edit_component" id="banner_horizontal_edit_component" onclick="editBannerHorizontalComponentFunction(this)"><i class='icon-pencil' style='padding-right:5px'></i></span>
				{*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*}
				<span class="trash" id="banner_horizontal_delete_component" onclick="trashBannerHorizontalComponentFunction(this)"><i class='icon-trash' style='padding-right:5px'></i></span>
				<div class="banner_preview layout_div" >
                                    {*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*}
                                    {*<img class="banner_preview_image" src="{$banner_horizontal_sliding}"/>*}{*variable contains HTML content, Can not escape this*}
                                    <div class="bannerHorizontalSlide">
                                        <h4 class="comp_heading" id="bannerHorizontalSlide_comp_heading">{l s='Horizontal Sliding Banner' mod='kbmobileapp'}</h4>
                                        <div class="slidingBannersScroll">
                                                <div class="slidingBanners" id="slidingBList">
                                                        <div class="bannerHorizontalSlideList" id="bannerHorizontalSlideList1">
                                                            <span class="BHSSection">    
                                                                <img id="bannerHorizontalSlideimg1" class="bannerHorizontalSlideimg1" src="{$preview_img_dir}BHS1.jpg"/>
                                                                <p class="elem_heading" id="bannerHorizontalSlide_elem_heading1">{l s='Horizontal Design Banner1' mod='kbmobileapp'}</p>
                                                            </span>
				</div>
                                                        <div class="bannerHorizontalSlideList" id="bannerHorizontalSlideList2">
                                                            <span class="BHSSection">    
                                                                <img id="bannerHorizontalSlideimg2" class="bannerHorizontalSlideimg2" src="{$preview_img_dir}BHS2.jpg"/>
                                                                <p class="elem_heading" id="bannerHorizontalSlide_elem_heading2">{l s='Horizontal Design Banner2' mod='kbmobileapp'}</p>
                                                            </span>    
                                                        </div>
                                                </div>
                                        </div>
                                    </div>
                                    {*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*}
                                </div>
			</li>
		</div>
		<div class="banner-grid" id="banner_grid_id" style="display:none;">
			<li class="slide" id="component_position">
				<span class="slideTitle">{l s='Banner-Grid' mod='kbmobileapp'} </span>
                                {*Start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*}
                                <span class="edit_component" id="banner_grid_edit_component" onclick="editBannerGridComponentFunction(this)"><i class='icon-pencil' style='padding-right:5px'></i></span>
				{*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*}
				<span class="trash" id="banner_grid_delete_component" onclick="trashBannerGridComponentFunction(this)"><i class='icon-trash' style='padding-right:5px'></i></span>
				<div class="banner_preview layout_div" >
                                    {*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*}
                                    {*<img class="banner_preview_image" src="{$banner_grid}"/>*}{*variable contains HTML content, Can not escape this*}
                                    <div class="bannerGrid">						
                                        <h4 class="comp_heading" id="bannerGrid_comp_heading">{l s='Grid Banners' mod='kbmobileapp'}</h4>
                                        <div class="bannerGridRow" id="bannerGRow1">
                                                <div class="bannerGridList">
                                                    <span class="BGSection">
                                                        <img id="bannerGridimg" class="bannerGridimg1" src="{$preview_img_dir}BS1.jpg"/>
                                                        <p class="elem_heading"  id="bannerGrid_elem_heading1">{l s='Grid Banner 1' mod='kbmobileapp'}</p>
                                                    </span>    
				</div>
                                                <div class="bannerGridList">
                                                    <span class="BGSection">
                                                        <img id="bannerGridimg" class="bannerGridimg2" src="{$preview_img_dir}BS2.jpg"/>
                                                        <p class="elem_heading"  id="bannerGrid_elem_heading2">{l s='Grid Banner 2' mod='kbmobileapp'}</p>
                                                    </span>    
                                                </div>
                                                <div class="bannerGridList">
                                                    <span class="BGSection">
                                                        <img id="bannerGridimg" class="bannerGridimg3" src="{$preview_img_dir}BS3.jpg"/>
                                                        <p class="elem_heading"  id="bannerGrid_elem_heading3">{l s='Grid Banner 3' mod='kbmobileapp'}</p>
                                                    </span>    
                                                </div>
                                                <div class="bannerGridList">
                                                    <span class="BGSection">
                                                        <img id="bannerGridimg" class="bannerGridimg4" src="{$preview_img_dir}BS4.jpg"/>
                                                        <p class="elem_heading"  id="bannerGrid_elem_heading4">{l s='Grid Banner 4' mod='kbmobileapp'}</p>
                                                    </span>    
                                                </div>
                                            </div>
                                            <div class="bannerGridRow1" id="bannerGRow" style="display:none">
                                            <div class="bannerGridList">
                                                <span class="BGSection">
                                                    <img id="bannerGridimg1" class="bannerGridimg" src="{$preview_img_dir}product.jpg"/>
                                                    <p class="elem_heading"  id="bannerGrid_elem_heading">{l s='Grid Banner 1' mod='kbmobileapp'}</p>
                                                </span>    
                                            </div>
                                        </div>
                                    </div>
                                            
                                    {*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*}    
                                </div>
			</li>
		</div>
		<div class="banner-countdown" id="banner_countdown_id" style="display:none;">
			<li class="slide" id="component_position">
				<span class="slideTitle">{l s='Banner With Countdown Timer' mod='kbmobileapp'}</span>
                                <span class="edit_component" id="banner_countdown_delete_component" onclick="editBannerCountdownComponentFunction(this)"><i class='icon-pencil' style='padding-right:5px'></i></span>
				<span class="trash" id="banner_countdown_delete_component" onclick="trashBannerCountdownComponentFunction(this)"><i class='icon-trash' style='padding-right:5px'></i></span>
				<div class="banner_preview layout_div" >
                                {*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*}
                                    {*<img class="banner_preview_image" src="{$banner_countdown}"/>*}{*variable contains HTML content, Can not escape this*}
                                    <h4 class="comp_heading" id="bannerCountdown_comp_heading">{l s='Countdown Banner' mod='kbmobileapp'}</h4>
					<div class="countdownlist" id="bannerCountdownList">
					<div class="countdownlistContent">
                                            <div class="countdownBackground" id="bannerCountdownimg" style="background:url('{$preview_img_dir}flash-sale.jpg')">
                                                    <div id="days"></div>
                                                    <div class="countDownTimer" style="background:transparent;">
                                                            <span class="timer">23 {l s='Hours' mod='kbmobileapp'}</span>
                                                            <span class="timer">21 {l s='Minutes' mod='kbmobileapp'}</span>
                                                            <span class="timer">49 {l s='Seconds' mod='kbmobileapp'}</span>
				</div>
                                            </div>
                                            <p class="elem_heading" id="bannerCountdown_elem_heading">{l s='Banner With Countdown Timer' mod='kbmobileapp'}</p>
					</div>
                                        <div class="countdownlistContentContainer1" style="display:none;">
                                        <div class="countdownlistContent">
                                            <div class="countdownBackground" id="bannerCountdownimg1" style="background:url(countdown_banner_img_url)">
                                                    <div id="days"></div>
                                                    <div class="countDownTimer" style="background:background_color_of_timer_text;">
                                                            <span class="timer">23 {l s='Hours' mod='kbmobileapp'}</span>
                                                            <span class="timer">21 {l s='Minutes' mod='kbmobileapp'}</span>
                                                            <span class="timer">49 {l s='Seconds' mod='kbmobileapp'}</span>
                                                    </div>
                                            </div>
                                            <p class="elem_heading" id="bannerCountdown_elem_heading">{l s='Banner With Countdown Timer' mod='kbmobileapp'}</p>
					</div>
                                        </div>
					</div>
					
                                {*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*}        
                                </div>
			</li>
		</div>
		<div class="product-square" id="product_square_id" style="display:none;">
			<li class="slide" id="component_position">
				<span class="slideTitle">{l s='Products-Square' mod='kbmobileapp'} </span>
                                {*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*}   
{*                                <span class="edit_component" id="product_square_edit_component" onclick="editProductHorizontalComponentFunction(this)"><i class='icon-pencil' style='padding-right:5px'></i></span>*}
				<span class="edit_component" id="product_square_edit_component" onclick="editProductSquareComponentFunction(this)"><i class='icon-pencil' style='padding-right:5px'></i></span>
                                {*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*}   
				<span class="trash" id="product_square_delete_component" onclick="trashProductSquareComponentFunction(this)"><i class='icon-trash' style='padding-right:5px'></i></span>
				<div class="banner_preview layout_div" >
                                    {*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*}   
                                    {*<img class="banner_preview_image" src="{$product_square}"/>*}{*variable contains HTML content, Can not escape this*}
                                    <div class="productSquare">
                                        <h4 id="productSquare_comp_heading" class="comp_heading">{l s='Products Square' mod='kbmobileapp'}</h4>
                                        <div class="productSquareList" id="productSList">
                                                <img id="productSquareimg" class="productSquareimg" src="{$preview_img_dir}product-square.jpg"/>
                                                <div class="productContent">
                                                        <div class="productInfo">
                                                                <h5 id ="productName">{l s='Product Name' mod='kbmobileapp'}</h5>
                                                                <h6 id="productPrice" class="productPPrice">$100</h6>
				</div>
                                                        <div class="wishlistProduct">
                                                                <i class="fa fa-heart-o"></i>
                                                        </div>
                                                </div>
                                        </div>
                                        <div class="productSquareList1" id="productSList1" style="display:none;">
                                                <img id="productSquareimg1" class="productSquareimg1" src="{$preview_img_dir}product-square.jpg"/>
                                                <div class="productContent">
                                                        <div class="productInfo">
                                                                <h5 id ="productName">{l s='Product Name' mod='kbmobileapp'}</h5>
                                                                <h6 id="productPrice" class="productPPrice">$100</h6>
                                                        </div>
                                                        <div class="wishlistProduct">
                                                                <i class="fa fa-heart-o"></i>
                                                        </div>
                                                </div>
                                        </div>                        
                                        
                                    </div>
                                    {*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*}       
                                </div>
			</li>
		</div>
		<div class="Hproduct-slide" id="product_horizontal_slide_id" style="display:none;">
			<li class="slide" id="component_position">
				<span class="slideTitle">{l s='Products-Horizontal Sliding ' mod='kbmobileapp'}</span>
                                <span class="edit_component" id="product_horizontal_edit_component" onclick="editProductHorizontalComponentFunction(this)"><i class='icon-pencil' style='padding-right:5px'></i></span>
				<span class="trash" id="product_horizontal_delete_component" onclick="trashProductHorizontalComponentFunction(this)"><i class='icon-trash' style='padding-right:5px'></i></span>
				<div class="banner_preview layout_div" >
                                {*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*} 
                                    {*<img class="banner_preview_image" src="{$product_horizontal_sliding}"/>*}{*variable contains HTML content, Can not escape this*}
                                <div class="slidingBannersScroll">
                                    <h4 class="comp_heading" id="slidingProducts_comp_heading">{l s='Horizontal Products' mod='kbmobileapp'}</h4>
                                        <div class="slidingProducts" id="slidingPRow1">							
                                            <div class="productSlideList" id="productSlideList1">
                                                <img class="slidingProductsimg" id="slidingProductsimg1" src="{$preview_img_dir}product1.jpg"/>
                                                <div class="productContent">
                                                    <div class="productInfo">
                                                        <h5 id ="productName">{l s='Product Name' mod='kbmobileapp'}</h5>
                                                        <h6 id="productPrice" class="productPPrice">$100</h6>
				</div>
                                                    <div class="wishlistProduct">
                                                        <i class="fa fa-heart-o"></i>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="productSlideList" id="productSlideList2">
                                                <img class="slidingProductsimg" id="slidingProductsimg2" src="{$preview_img_dir}product2.jpg"/>
                                                <div class="productContent">
                                                    <div class="productInfo">
                                                        <h5 id ="productName">{l s='Product Name' mod='kbmobileapp'}</h5>
                                                        <h6 id="productPrice" class="productPPrice">$100</h6>
                                                    </div>
                                                    <div class="wishlistProduct">
                                                        <i class="fa fa-heart-o"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="productSlideList" id="productSlideList3">
                                                <img class="slidingProductsimg" id="slidingProductsimg3" src="{$preview_img_dir}product3.jpg"/>
                                                <div class="productContent">
                                                    <div class="productInfo">
                                                        <h5 id ="productName">{l s='Product Name' mod='kbmobileapp'}</h5>
                                                        <h6 id="productPrice" class="productPPrice">$100</h6>
                                                    </div>
                                                    <div class="wishlistProduct">
                                                        <i class="fa fa-heart-o"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="slidingProducts" id="slidingPRow" style="display:none;">							
                                            <div class="productSlideList">
                                                <img class="slidingProductsimg" id="slidingProductsimg" src="{$preview_img_dir}product1.jpg"/>
                                                <div class="productContent">
                                                    <div class="productInfo">
                                                        <h5 class ="productName" id="ProductName">{l s='Product Name' mod='kbmobileapp'}</h5>
                                                        <h6 class="productPPrice" id="ProductPrice">$100</h6>
                                                    </div>
                                                    <div class="wishlistProduct">
                                                        <i class="fa fa-heart-o"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>                
                                    </div>
                                    {*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*}     
                                </div>
			</li>
		</div>
		<div class="product-grid" id="product_grid_id" style="display:none;">
			<li class="slide" id="component_position">
				<span class="slideTitle">{l s='Products-Grid' mod='kbmobileapp'} </span>
                                {*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*}  
                                <span class="edit_component" id="product_grid_edit_component" onclick="editProductGridComponentFunction(this)"><i class='icon-pencil' style='padding-right:5px'></i></span>
				{*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*}  
				<span class="trash" id="product_grid_delete_component"  onclick="trashProductGridComponentFunction(this)"><i class='icon-trash' style='padding-right:5px'></i></span>
				<div class="banner_preview layout_div" >
                                    {*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*}     
					{*<img class="banner_preview_image" src="{$product_grid}"/>*}{*variable contains HTML content, Can not escape this*}
                                    <div class="productGrid">						
                                        <p class="comp_heading" id="productGrid_comp_heading">{l s='Product Grid' mod='kbmobileapp'}</p>
{*                                        <div class="productGridRowContainer">*}
                                        <div class="productGridRow" id="productGRow" style="display:none;">
                                            <div class="productGridList">
                                                <img id="productGridimg"  src="{$preview_img_dir}pg1.jpg"/>
                                                <div class="productContent">
                                                    <div class="productInfo">
                                                        <h5 id ="productName">{l s='Product Name' mod='kbmobileapp'}</h5>
                                                        <h6 id="productPrice" class="productPPrice">$100</h6>
				</div>
                                                    <div class="wishlistProduct">
                                                        <i class="fa fa-heart-o"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
{*                                        </div>                *}
                                        <div class="productGridRow1" id="productGRow1">
                                            <div class="productGridList" >
                                                <img id="productGridimg1"  src="{$preview_img_dir}pg1.jpg"/>
                                                <div class="productContent">
                                                    <div class="productInfo">
                                                        <h5 id ="productName">{l s='Product Name' mod='kbmobileapp'}</h5>
                                                        <h6 id="productPrice" class="productPPrice">$100</h6>
                                                    </div>
                                                    <div class="wishlistProduct">
                                                        <i class="fa fa-heart-o"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="productGridList">
                                                <img id="productGridimg1"  src="{$preview_img_dir}pg2.jpg"/>
                                                <div class="productContent">
                                                    <div class="productInfo">
                                                        <h5 id ="productName">{l s='Product Name' mod='kbmobileapp'}</h5>
                                                        <h6 id="productPrice" class="productPPrice">$100</h6>
                                                    </div>
                                                    <div class="wishlistProduct">
                                                        <i class="fa fa-heart-o"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="productGridList">
                                                <img id="productGridimg1"  src="{$preview_img_dir}pg3.jpg"/>
                                                <div class="productContent">
                                                    <div class="productInfo">
                                                        <h5 id ="productName">{l s='Product Name' mod='kbmobileapp'}</h5>
                                                        <h6 id="productPrice" class="productPPrice">$100</h6>
                                                    </div>
                                                    <div class="wishlistProduct">
                                                        <i class="fa fa-heart-o"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="productGridList">
                                                <img id="productGridimg1"  src="{$preview_img_dir}pg4.jpg"/>
                                                <div class="productContent">
                                                    <div class="productInfo">
                                                        <h5 id ="productName">{l s='Product Name' mod='kbmobileapp'}</h5>
                                                        <h6 id="productPrice" class="productPPrice">$100</h6>
                                                    </div>
                                                    <div class="wishlistProduct">
                                                        <i class="fa fa-heart-o"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*}         
                                </div>
			</li>
		</div>
		<div class="product-lastAccess" id="product_last_accessed_id" style="display:none;">
			<li class="slide" id="component_position">
				<span class="slideTitle">{l s='Products Recently accessed' mod='kbmobileapp'}</span>
                                <span class="trash" id="last_access_delete_component" onclick="trashLastAccessComponentFunction(this)"><i class='icon-trash' style='padding-right:5px'></i></span>
                                <div class="banner_preview layout_div" >
{*					<img class="banner_preview_image" src="{$product_recent_access}"/>*}{*variable contains HTML content, Can not escape this*}
                                    {*start:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*} 
                                    {*<img class="banner_preview_image" src="{$product_horizontal_sliding}"/>*}{*variable contains HTML content, Can not escape this*}
                                <div class="slidingBannersScroll">
                                    <h4 class="comp_heading" id="slidingRecentProducts_comp_heading">{l s='Products Recently accessed' mod='kbmobileapp'}</h4>
                                        <div class="slidingProducts" id="slidingRecentPRow1">							
                                            <div class="productSlideList" id="productRecentSlideList1">
                                                <img class="slidingProductsimg" id="slidingRecentProductsimg1" src="{$preview_img_dir}product.jpg"/>
                                                <div class="productContent">
                                                    <div class="productInfo">
                                                        <h5 id ="productName">{l s='Product Name' mod='kbmobileapp'}</h5>
                                                        <h6 id="productPrice" class="productPPrice">$100</h6>
				</div>
                                                    <div class="wishlistProduct">
                                                        <i class="fa fa-heart-o"></i>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="productSlideList" id="productRecentSlideList2">
                                                <img class="slidingProductsimg" id="slidingRecentProductsimg2" src="{$preview_img_dir}product.jpg"/>
                                                <div class="productContent">
                                                    <div class="productInfo">
                                                        <h5 id ="productName">{l s='Product Name' mod='kbmobileapp'}</h5>
                                                        <h6 id="productPrice" class="productPPrice">$100</h6>
                                                    </div>
                                                    <div class="wishlistProduct">
                                                        <i class="fa fa-heart-o"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="productSlideList" id="productRecentSlideList3">
                                                <img class="slidingProductsimg" id="slidingRecentProductsimg3" src="{$preview_img_dir}product.jpg"/>
                                                <div class="productContent">
                                                    <div class="productInfo">
                                                        <h5 id ="productName">{l s='Product Name' mod='kbmobileapp'}</h5>
                                                        <h6 id="productPrice" class="productPPrice">$100</h6>
                                                    </div>
                                                    <div class="wishlistProduct">
                                                        <i class="fa fa-heart-o"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="slidingProducts" id="slidingRecentPRow" style="display:none;">							
                                            <div class="productSlideList">
                                                <img class="slidingProductsimg" id="slidingRecentProductsimg" src="{$preview_img_dir}product.jpg"/>
                                                <div class="productContent">
                                                    <div class="productInfo">
                                                        <h5 class ="productName" id="ProductName">{l s='Product Name' mod='kbmobileapp'}</h5>
                                                        <h6 class="productPPrice" id="ProductPrice">$100</h6>
                                                    </div>
                                                    <div class="wishlistProduct">
                                                        <i class="fa fa-heart-o"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>                
                                    </div>
                                    {*end:changes made by Aayushi Agarwal on 27th March 2020 to make preview changes*}     
                                </div>
			</li>
		</div>
                <input type="hidden" id="number_of_component" value="0"/>
                <input type="hidden" id="id_layout" value="0"/>
                <input type="hidden" id="id_component_selected" value="0"/>
                <input type="hidden" id="id_layout_name_selected" value="0"/>
                <img id='kbsw_show_loader'  style="width:50px;height:50px;display:none;" src="{$loader}/show_loader.gif">
</div>
</div>
                    
                    <div id="banner_form_popup" style="display:none;">
                        
                    </div>
                    {*<div id="rm_return_form_popup" class="white_content" style="display:none;width: 50%;">
                            <a href="javascript:void(0)" id="rm_popup_close_icon" class="rm_popup_close_icon">&nbsp;</a>
                        
                        <div id="component_edit_popup" style="max-height: 500px;overflow-y: scroll;">
                        </div>*}
                        {*<div id="rm_fade" class="black_overlay" style="display: block;"></div>*}
        
        
{*</div>  *}
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="kbGDPRDialogueModel" class="modal loader fade" style="display: none;">
    <div class="modal-dialog" style='width:57%'>
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">x</button>
                <h4 class="modal-title">{l s='Edit Component' mod='kbmobileapp'}</h4>
                <div class="bootstrap">
		{*<div id="confirmation_block_modal" style="display:none;" class="module_confirmation conf confirm alert alert-success">
			<button type="button" class="close" data-dismiss="alert">×</button>
			<div id="success_message">
                        {l s='Data Saved successfully' mod='kbmobileapp'}
                        </div>
		</div>*}
		</div>
            </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                </div>
        </div>
    </div>
</div>
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="layoutNameModel" class="modal fade" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">x</button>
                <h4 class="modal-title">{l s='Manage Layouts' mod='kbmobileapp'}</h4>
            </div>
                <div class="modal-layout-body">
                </div>
                <div class="modal-footer">
                </div>
        </div>
    </div>
</div>
      <style>
            .loader {
           display:    none;
           position:   fixed;
           z-index:    99999;
           top:        0;
           left:       0;
           height:     100% ;
           width:      100% ;
           background: 
               url('{$loader}/show_loader.gif')
               50% 50%
               no-repeat;
           background-size: 70px;
       }
       </style>
<div class="modal"></div>
{*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer tohttp://www.prestashop.com for more information.
* We offer the best and most useful modules PrestaShop and modifications for your online store.
*
* @category  PrestaShop Module
* @author    knowband.com <support@knowband.com>
* @copyright 2016 Knowband
* @license   see file: LICENSE.txt
*
*}