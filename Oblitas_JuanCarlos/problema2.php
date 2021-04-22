<?php
//@author Juan Carlos Oblitas Nuñez
class Product
{
	private $price;
	public function __construct ($price=null) {
        $this->price = $price;
    }

    public function getPrice() {
        return $this->price;
    }

    public function setPrice($price) {
        $this->price = $price;
    }

}

class Voucher{
	private $discountMoney;
	private $discountPercentage;

	public function __construct ($discountMoney=null, $discountPercentage=null) {
        $this->discountMoney = $discountMoney;
        $this->discountPercentage = $discountPercentage;
    }

    public function getDiscountMoney() {
        return $this->discountMoney;
    }

    public function setDiscountMoney($discountMoney) {
        $this->discountMoney = $discountMoney;
    }

    public function getDiscountPercentage() {
        return $this->discountPercentage;
    }

    public function setDiscountPercentage($discountPercentage) {
        $this->discountPercentage = $discountPercentage;
    }
}

class VoucherV extends Voucher
{
	private const DISCOUNTMONEY = 0;
	private const DISCOUNTPERCENTAGE = 0.1;

    public function __construct ($discountMoney=self::DISCOUNTMONEY,$discountPercentage=self::DISCOUNTPERCENTAGE) {
		parent::__construct($discountMoney,$discountPercentage);
    }
}

class VoucherR extends Voucher
{
	private const DISCOUNTMONEY = 5;
	private const DISCOUNTPERCENTAGE = 0;

    public function __construct ($discountMoney=self::DISCOUNTMONEY,$discountPercentage=self::DISCOUNTPERCENTAGE) {
		parent::__construct($discountMoney,$discountPercentage);
    }
}

class VoucherS extends Voucher
{
	private const DISCOUNTMONEY = 0;
	private const DISCOUNTPERCENTAGE = 0.05;

    public function __construct ($discountMoney=self::DISCOUNTMONEY,$discountPercentage=self::DISCOUNTPERCENTAGE) {
		parent::__construct($discountMoney,$discountPercentage);
    }
}

class ProductA extends Product
{	
	private const PRICE = 10;

    public function __construct ($price=self::PRICE) {
		parent::__construct($price);
    }

}

class ProductB extends Product
{	
	private const PRICE = 8;

    public function __construct ($price=self::PRICE) {
		parent::__construct($price);
    }

}

class ProductC extends Product
{	
	private const PRICE = 12;

    public function __construct ($price=self::PRICE) {
		parent::__construct($price);
    }

}

class Basket
{
	private $products;
	private $vouchers;
	private const VALUECARTODISCOUNT=40;
	private const ADDPRODUCT="add product";
	private const ADDVOUCHER="add voucher";
	private const TYPEMONEY=" €<br>";
    private const DISCOUNTNOAPPLY="discount does not apply <br>";
	public function __construct ($products=null,$vouchers=null) {
        $this->products = $products;
        $this->vouchers = $vouchers;
    }

    public function getaddProductCar(){
    	$amountProductA=0;
    	$amountProductB=0;
    	$amountProductC=0;
    	$amountVoucherS=0;
    	$totalPrice=0;
    	$secuencia="Car:<br>";
    	$typeMoney=" €<br>";
    	foreach ($this->products as $product => $value) {
    		if($value instanceof ProductA){
    			$amountProductA++;
    			$totalPrice+=(new ProductA)->getPrice();
    			$secuencia.=self::ADDPRODUCT." A...".(new ProductA)->getPrice().self::TYPEMONEY;
    		}
    		if($value instanceof ProductB){
    			$amountProductB++;
    			$totalPrice+=(new ProductB)->getPrice();
    			$secuencia.=self::ADDPRODUCT." B...".(new ProductB)->getPrice().self::TYPEMONEY;
    		}
    		if($value instanceof ProductC){
    			$amountProductC++;
    			$totalPrice+=(new ProductC)->getPrice();
    			$secuencia.=self::ADDPRODUCT." C...".(new ProductC)->getPrice().self::TYPEMONEY;
    		}
    	}
    	if(!empty($this->vouchers)){
    		foreach ($this->vouchers as $voucher => $value) {
	    		if($value instanceof VoucherV){
	    			if(intdiv($amountProductA, 2)>=1){
	    				$totalPrice-=(new ProductA)->getPrice()*(new VoucherV)->getDiscountPercentage();
	    				$amountProductA-=2;
	    				$secuencia.=self::ADDVOUCHER." V...".((new VoucherV)->getDiscountPercentage()*100)." % discount in second product A<br>";
	    			}else{
	    				$secuencia.=self::ADDVOUCHER." V...".self::DISCOUNTNOAPPLY;
	    			}
	    		}
	    		if($value instanceof VoucherR){
	    			if($amountProductB>0){
	    				$totalPrice-=(new VoucherR)->getDiscountMoney();
	    				$amountProductB--;
	    				$secuencia.=self::ADDVOUCHER." R...".(new VoucherR)->getDiscountMoney()." € discount in product B<br>";
	    			}else{
	    				$secuencia.=self::ADDVOUCHER." R...".self::DISCOUNTNOAPPLY;
	    			}
	    		}
	    		if($value instanceof VoucherS){
	    			$amountVoucherS++;
	    			$secuencia.=self::ADDVOUCHER." S...".((new VoucherS)->getDiscountPercentage()*100)." % discount on a cart value over ".self::VALUECARTODISCOUNT." €<br>";
	    		}
    		}
    	}
    	//The S Voucher is only applied to the final price
    	return $secuencia."Total Price: ".$this->getPriceApplyingVoucherS($amountVoucherS, $totalPrice)." €";
    }

    public function getVouchers() {
        return $this->vouchers;
    }

    public function setVouchers($vouchers) {
        $this->vouchers = $vouchers;
    }

    public function getProducts() {
        return $this->vouchers;
    }

    public function setProducts($products) {
        $this->products = $products;
    }

    public function getPriceApplyingVoucherS($amountVoucherS, $totalPrice){
    	if($amountVoucherS>0 && $totalPrice>self::VALUECARTODISCOUNT){
    		return $this->getPriceApplyingVoucherS(--$amountVoucherS, $totalPrice*(1-(new VoucherS)->getDiscountPercentage()));
    	}
    	return $totalPrice;
    }

}

$pA=new ProductA;
$pC=new ProductC;
$vS=new VoucherS;
$vV=new VoucherV;
$pB=new ProductB;
$vR=new VoucherR;

/*Product A added + Product C added + Voucher S added + Product A added + Voucher V added + Product B added*/
$products = array($pA, $pC, $pA, $pB);
$vauchers= array($vS,$vV);
$basket=new Basket($products,$vauchers);
echo $basket->getaddProductCar();
echo "<br>";
/*Product A added + Voucher S added + Product A added + Voucher V added + Product B added + Voucher R added
+ Product C added + Product C added + Product C added.*/
$products = array($pA, $pA, $pB, $pC,$pC,$pC);
$vauchers= array($vS,$vV,$vR);
$basket->setProducts($products);
$basket->setVouchers($vauchers);
echo $basket->getaddProductCar();

?>