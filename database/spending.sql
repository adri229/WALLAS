SELECT s.idSpending as 'spending.id',
		SUM(s.quantity) as 'spending.quantity',
		t.idType as 'type.id',
		t.name as 'type.name'
FROM SPENDING s LEFT JOIN TYPE_SPENDING ts  ON s.idSpending = ts.spending LEFT JOIN TYPE t on ts.type = t.idType  
WHERE s.owner = 'adri229' AND ts.spending IS NOT NULL GROUP BY ts.type ORDER BY 'spending.id';