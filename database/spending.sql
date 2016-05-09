SELECT s.idSpending as 'spending.id',
		s.dateSpending as 'spending.date',
		s.quantity as 'spending.quantity',
		s.name as 'spending.name',
		s.owner as 'spending.owner',
		t.idType as 'type.id',
		t.name as 'type.name',
		t.owner as 'type.owner'
FROM SPENDING s LEFT JOIN TYPE_SPENDING ts  ON s.idSpending = ts.spending LEFT JOIN TYPE t on ts.type = t.idType 
WHERE s.owner = 'adri229' ORDER BY 'spending.id';