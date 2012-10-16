<?php
/**
 *    This file is part of OXID eShop Community Edition.
 *
 *    OXID eShop Community Edition is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation, either version 3 of the License, or
 *    (at your option) any later version.
 *
 *    OXID eShop Community Edition is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with OXID eShop Community Edition.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @link http://www.oxid-esales.com
 * @package views
 * @copyright (C) OXID eSales AG 2003-2009
 * @version OXID eShop CE
 * $Id: oxcmp_shop.php 16306 2009-02-05 10:28:05Z rimvydas.paskevicius $
 * 
 * Modified by Avenger to allow admin logins to front-end for inactive shops
 * 
 * @copyright (C) Avenger 2010
 * @version OXID eShop CE
 * $Id: pt_oxcmp_shop.php 1702 2010-02-04 06:22:13Z avenger $
 * 
 * Activate with: oxcmp_shop => powertemplate/pt_oxcmp_shop/pt_oxcmp_shop
 */

/**
 * Translarent shop manager (executed automatically), sets
 * registration information and current shop object.
 * @subpackage oxcmp
 */
 
class pt_oxcmp_shop extends pt_oxcmp_shop_parent
{
  /**
   * Marking object as component
   * @var bool
   */
  protected $_blIsComponent = true;

  /**
  * Executes parent::render() and returns active shop object.
  *
  * @return  object  $this->oActShop active shop object
  */
  public function render()
  {
    //Avenger
    $myConfig = $this->getConfig();
    // is shop active?
    $oShop = $myConfig->getActiveShop();
    if (!$oShop->oxshops__oxactive->value && !$this->isAdmin() && 'oxstart' != $myConfig->getActiveView()->getClassName()) 
    {
      $logged_in=false;
      $sUser=$_GET['user'];
      $sPassword=$_GET['pass'];
      if ($sUser && $sPassword)
      {
        $_GET['lgn_usr']=$sUser ;
        $_GET['lgn_pwd']=$sPassword;
        $oUser=oxnew('oxcmp_user');
        $logged_in=$oUser->login($sUser, $sPassword);
        $logged_in=$logged_in<>'user';
        $_SESSION['logged_in']=$logged_in;
      }
      else
      {
        $logged_in=$_SESSION['logged_in'];
      }
      if (!$logged_in)
      {
        oxUtils::getInstance()->redirect( 'offline.html',false,302);
        //Use alternate template for maintenance announcement
        //oxUtils::getInstance()->redirect( 'maintenance.html',false,302);
      }
    }
    $sShopLogo = $myConfig->getConfigParam( 'sShopLogo' );
    if ( $sShopLogo && file_exists( $myConfig->getAbsImageDir().'/'.$sShopLogo ) ) 
    {
      $this->_oParent->setShopLogo($sShopLogo);
      // Passing to view. Left for compatibility reasons for a while. Will be removed in future
      $this->_oParent->addTplParam( 'shoplogo', $this->_oParent->getShopLogo() );
    }
    //Avenger
    return $oShop;
  }
}
?>