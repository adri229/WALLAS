
SELECT 
		SUM(s.quantity) as 'spending.quantity'
		
FROM SPENDING s LEFT JOIN TYPE_SPENDING ts  ON s.idSpending = ts.spending LEFT JOIN TYPE t on ts.type = t.idType  
WHERE s.owner = 'adri229' AND s.dateSpending BETWEEN '2016-01-01' AND '2016-06-01' AND ts.type IS NULL;






SELECT 
		SUM(s.quantity) as 'spending.quantity'
		
FROM SPENDING s 
WHERE s.owner = 'adri229' AND s.dateSpending BETWEEN '2016-01-01' AND '2016-6-01';




SELECT SUM(s.quantity) as 'spending.quantity',
                t.idType as 'type.id',
                t.name as 'type.name'
        FROM SPENDING s LEFT JOIN TYPE_SPENDING ts  ON s.idSpending = ts.spending LEFT JOIN TYPE t on ts.type = t.idType  
        WHERE s.owner = 'adri229'  AND s.dateSpending BETWEEN '2016-01-01' AND '2016-06-01' AND ts.spending IS NOT NULL GROUP BY ts.type
/*
SELECT 
		SUM(s.quantity) as 'spending.quantity',
		t.idType as 'type.id',
		t.name as 'type.name'
FROM SPENDING s LEFT JOIN TYPE_SPENDING ts  ON s.idSpending = ts.spending LEFT JOIN TYPE t on ts.type = t.idType  
WHERE s.owner = 'adri229' AND s.dateSpending BETWEEN '2016-01-01' AND '2016-6-01';

*/