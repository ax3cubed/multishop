<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
/**
 * Description of ThemeViewControllerTrait
 *
 * @author kwlok
 */
trait ThemeViewControllerTrait 
{
    public function getThemeStyleImage(ThemeStyle $style,$device=null)
    {
        return $this->getAssetsURL($style->imagePathAlias).'/'.$style->getImage($device);
    }
    /**
     * Return the one theme image (using the default style or the first found style) as representative
     * @param Theme $theme
     */
    public function getThemeStoreImage(Theme $theme)
    {
        $image = '';
        foreach ($theme->availableStyles as $id => $style) {
            $image = CHtml::image($this->getThemeStyleImage($style),'Theme style '.$id,['class'=>$theme->id.' '.$id.' style-image']);
            break;
        }
        return $image;
    }
    /**
     * Return all theme images (with styles)
     * @param Theme $theme
     * @param type $currentStyle
     * @return type
     */
    public function getThemeImages(Theme $theme,$currentStyle=null)
    {
        $displayImages = []; 
        foreach ($theme->availableStyles as $id => $style) {
            $displayImages[$id]['style'] = $style;//store the style
            if ($id==$currentStyle){//show current matched style image
                $displayImages[$id]['show'] = true;
                $displayImages[$id]['current'] = true;
            }
            elseif ($id==Tii::defaultStyle())//if not matched, show default style image first
                $displayImages[$id]['show'] = true;
            else
                $displayImages[$id]['show'] = false;
        }
        //loop through one more to set only matched current style to show, rest image hide initial
        foreach ($displayImages as $id => $data) {
            if (isset($data['current']) && $data['current']){
                foreach ($displayImages as $_id => $_data) {
                    if ($id!=$_id){
                        $displayImages[$_id]['show'] = false;//set all to false; only show the matched one
                        break;
                    }
                }
            }
        }
        
        $images = '';
        foreach ($displayImages as $id => $data) {
            $images .= CHtml::image($this->getThemeStyleImage($data['style']),'Theme style '.$id,['class'=>$theme->id.' '.$id.' style-image '.($data['show']?'selected':'')]);
        }
        return $images;
    }
    
    public function getThemeStyleSelections(Theme $theme)
    {
        $selections = new CMap();
        foreach ($theme->availableStyles as $style) {
            $selections->add($style->id,$style->getName(user()->getLocale()));
        }
        return $selections->toArray();
    }    
    
}
