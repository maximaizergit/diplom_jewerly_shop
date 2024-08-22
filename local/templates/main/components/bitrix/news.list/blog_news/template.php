<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>
<div class="news-list">
<?if($arParams["DISPLAY_TOP_PAGER"]):?>
	<?=$arResult["NAV_STRING"]?><br />
<?endif;?>
<?foreach($arResult["ITEMS"] as $arItem):?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	?>

    <section>
        <div class="container">
            <div class="row">
                <div class="col-sm-9">
                    <div class="blog-post-area">
                        <h2 class="title text-center">Latest From our Blog</h2>
                        <div class="single-blog-post">
                            <a href="<?=$arItem["DETAIL_PAGE_URL"]?>"> <h3><?=$arItem["NAME"]?></h3></a>
                            <div class="post-meta">
                                <ul>
                                    <li><i class="fa fa-clock-o"></i> <?=$arItem["DISPLAY_ACTIVE_FROM"]?></li>
                                </ul>
                                <span>
										<i class="fa fa-star"></i>
										<i class="fa fa-star"></i>
										<i class="fa fa-star"></i>
										<i class="fa fa-star"></i>
										<i class="fa fa-star-half-o"></i>
								</span>
                            </div>
                            <a href="<?=$arItem["DETAIL_PAGE_URL"]?>">
                                <img  src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>"
                                      width="<?=$arItem["PREVIEW_PICTURE"]["WIDTH"]?>"
                                      height="<?=$arItem["PREVIEW_PICTURE"]["HEIGHT"]?>"
                                      alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>"
                                      title="<?=$arItem["PREVIEW_PICTURE"]["TITLE"]?>">
                            </a>
                            <p><?=$arItem["PREVIEW_TEXT"]?></p>
                            <a  class="btn btn-primary" href="<?=$arItem["DETAIL_PAGE_URL"]?>">Read More</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?endforeach;?>
<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<br /><?=$arResult["NAV_STRING"]?>
<?endif;?>
</div>



