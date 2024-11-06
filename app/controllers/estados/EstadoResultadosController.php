<?php
// EstadoResultadosController.php
class EstadoResultadosController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }


    public function obtenerEstadoResultados($id_estado) {
        // Consulta para obtener los detalles del estado de resultados
        $sql = "SELECT df.*, ef.anio, c.nombre AS nombre_cuenta, tc.nombre_tipo AS tipo, cl.nombre_clasificacion AS clasificacion
                FROM detalle_estado_financiero df
                INNER JOIN cuentas c ON df.id_cuenta = c.id_cuenta
                INNER JOIN tipos_cuenta tc ON c.id_tipo = tc.id_tipo
                INNER JOIN clasificaciones cl ON c.id_clasificacion = cl.id_clasificacion
                INNER JOIN estados_financieros ef ON df.id_estado = ef.id_estado
                WHERE df.id_estado = :id_estado";
                
        $query = $this->pdo->prepare($sql);
        $query->bindParam(':id_estado', $id_estado);
        $query->execute();
        $detalles = $query->fetchAll(PDO::FETCH_ASSOC);
    
        // Inicializar variables para totales
        $anio = $detalles[0]['anio'] ?? null;
        $agrupados = [];
        $totalIngresosOperacion = 0;
        $totalCostosYGastosOperacion = 0;
        $totalIngresosNoOperacion = 0;
        $totalCostosYGastosNoOperacion = 0;
    
        // Agrupar datos y calcular los totales por clasificación y tipo
        foreach ($detalles as $detalle) {
            $clasificacion = $detalle['clasificacion'];
            $tipo = $detalle['tipo'];
            $saldo = $detalle['saldo'];
    
            if (!isset($agrupados[$clasificacion])) {
                $agrupados[$clasificacion] = ['tipos' => [], 'total' => 0];
            }
            if (!isset($agrupados[$clasificacion]['tipos'][$tipo])) {
                $agrupados[$clasificacion]['tipos'][$tipo] = ['cuentas' => [], 'total' => 0];
            }
    
            // Sumar el saldo al tipo y a la clasificación
            $agrupados[$clasificacion]['tipos'][$tipo]['cuentas'][] = $detalle;
            $agrupados[$clasificacion]['tipos'][$tipo]['total'] += $saldo;
            $agrupados[$clasificacion]['total'] += $saldo;
    
            // Calcular totales específicos
            if ($clasificacion === 'Ingreso' && $tipo === 'Operación') {
                $totalIngresosOperacion += $saldo;
            } elseif ($clasificacion === 'Ingreso' && $tipo === 'No Operación') {
                $totalIngresosNoOperacion += $saldo;
            } elseif ($clasificacion === 'Costo y Gasto' && $tipo === 'Operación') {
                $totalCostosYGastosOperacion += $saldo;
            } elseif ($clasificacion === 'Costo y Gasto' && $tipo === 'No Operación') {
                $totalCostosYGastosNoOperacion += $saldo;
            }
        }
    
        // Calcular utilidad de operación solo con ingresos y costos de operación
        $utilidadOperacion = $totalIngresosOperacion - $totalCostosYGastosOperacion;
        // Calcular utilidad neta incluyendo todos los ingresos y gastos
        $utilidadNeta = $utilidadOperacion + $totalIngresosNoOperacion - $totalCostosYGastosNoOperacion;
    
        return [
            'anio' => $anio,
            'agrupados' => $agrupados,
            'totalIngresosOperacion' => $totalIngresosOperacion,
            'totalCostosYGastosOperacion' => $totalCostosYGastosOperacion,
            'totalIngresosNoOperacion' => $totalIngresosNoOperacion,
            'totalCostosYGastosNoOperacion' => $totalCostosYGastosNoOperacion,
            'utilidadOperacion' => $utilidadOperacion,
            'utilidadNeta' => $utilidadNeta
        ];
    }
    
    
}
