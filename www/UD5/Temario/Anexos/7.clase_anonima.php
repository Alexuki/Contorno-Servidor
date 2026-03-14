<?php

$triangulo1 = new class(10, 5) {
	public float $base;
	public float $altura;

	public function __construct(float $base, float $altura)
	{
		$this->base = $base;
		$this->altura = $altura;
	}

	public function area(): float
	{
		return ($this->base * $this->altura) / 2;
	}
};

$triangulo2 = new class(8, 12) {
	public float $base;
	public float $altura;

	public function __construct(float $base, float $altura)
	{
		$this->base = $base;
		$this->altura = $altura;
	}

	public function area(): float
	{
		return ($this->base * $this->altura) / 2;
	}
};

echo "Area triangulo 1 (base={$triangulo1->base}, altura={$triangulo1->altura}): {$triangulo1->area()}<br>";
echo "Area triangulo 2 (base={$triangulo2->base}, altura={$triangulo2->altura}): {$triangulo2->area()}<br>";

?>