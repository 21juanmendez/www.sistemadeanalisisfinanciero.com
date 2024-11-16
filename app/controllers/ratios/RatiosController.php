<?php
class RatiosController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    //funcion para obtener los ratios promedio de la industria
    public function obtenerRatiosPromedioIndustria($id_empresa)
    {
        $indicadoresFinancieros = [
            "Capital de Trabajo",
            "Grado de Endeudamiento",
            "Grado de Propiedad",
            "Margen Neto",
            "Prueba Ácida (Razón Rápida)",
            "Razón de Capital de Trabajo",
            "Razón de Circulante (Liquidez Corriente)",
            "Razón de Endeudamiento Patrimonial",
            "Rentabilidad sobre Activos (ROA)",
            "Rentabilidad sobre Patrimonio (ROE)",
            "Índice de Eficiencia Operativa"
        ];
        
        $promedios = [];
        $sql = "SELECT ri.nombre_ratio_industria, ri.promedio from empresa em
            INNER JOIN ratios_industrias ri ON em.id_tipoEmpresa = ri.id_tipoEmpresa
            WHERE em.id_empresa = $id_empresa ORDER BY ri.nombre_ratio_industria";
        $statement = $this->pdo->prepare($sql);
        $statement->execute();

        //obtener los promedios de los ratios
        $ratios_promedio = $statement->fetchAll();

        foreach ($ratios_promedio as $ratio) {
            //si el nombre de ratio no esta en el array de indicadores financieros, poner N/A
            if (!in_array($ratio['nombre_ratio_industria'], $indicadoresFinancieros)) {
                $promedios[$ratio['nombre_ratio_industria']] = 'N/A';
            } else {
                $promedios[$ratio['nombre_ratio_industria']] = $ratio['promedio'];
            }
        }
        return $promedios;
    }
    //Función para calcular la Razón de Circulante
    public function calcularLiquidezCorrientePorAno($id_empresa)
    {
        // Consulta SQL para obtener saldos de activos corrientes y pasivos corrientes agrupados por año
        $sql = "SELECT ef.anio, 
                       SUM(CASE WHEN cl.nombre_clasificacion = 'Activo' AND tc.nombre_tipo = 'Corriente' THEN df.saldo ELSE 0 END) AS activo_corriente,
                       SUM(CASE WHEN cl.nombre_clasificacion = 'Pasivo' AND tc.nombre_tipo = 'Corriente' THEN df.saldo ELSE 0 END) AS pasivo_corriente
                FROM detalle_estado_financiero df
                INNER JOIN cuentas c ON df.id_cuenta = c.id_cuenta
                INNER JOIN clasificaciones cl ON c.id_clasificacion = cl.id_clasificacion
                INNER JOIN tipos_cuenta tc ON c.id_tipo = tc.id_tipo
                INNER JOIN estados_financieros ef ON df.id_estado = ef.id_estado
                WHERE ef.id_empresa = :id_empresa
                GROUP BY ef.anio";

        $query = $this->pdo->prepare($sql);
        $query->bindParam(':id_empresa', $id_empresa, PDO::PARAM_INT);
        $query->execute();
        $resultados = $query->fetchAll(PDO::FETCH_ASSOC);

        // Calcular la razón de circulante para cada año
        $ratiosPorAno = [];
        foreach ($resultados as $resultado) {
            $activo_corriente = $resultado['activo_corriente'];
            $pasivo_corriente = $resultado['pasivo_corriente'];
            $liquidez_corriente = $pasivo_corriente > 0 ? $activo_corriente / $pasivo_corriente : null;

            $ratiosPorAno[$resultado['anio']] = [
                'liquidez_corriente' => $liquidez_corriente
            ];
        }

        return $ratiosPorAno;
    }
    // Función para calcular la Prueba Ácida
    public function calcularPruebaAcidaPorAno($id_empresa)
    {
        // Consulta SQL para obtener saldos de activos corrientes, pasivos corrientes y el inventario agrupados por año
        $sql = "SELECT ef.anio, 
                       SUM(CASE WHEN cl.nombre_clasificacion = 'Activo' AND tc.nombre_tipo = 'Corriente' THEN df.saldo ELSE 0 END) AS activo_corriente,
                       SUM(CASE WHEN cl.nombre_clasificacion = 'Pasivo' AND tc.nombre_tipo = 'Corriente' THEN df.saldo ELSE 0 END) AS pasivo_corriente,
                       SUM(CASE WHEN c.nombre = 'Inventarios' THEN df.saldo ELSE 0 END) AS inventario
                FROM detalle_estado_financiero df
                INNER JOIN cuentas c ON df.id_cuenta = c.id_cuenta
                INNER JOIN clasificaciones cl ON c.id_clasificacion = cl.id_clasificacion
                INNER JOIN tipos_cuenta tc ON c.id_tipo = tc.id_tipo
                INNER JOIN estados_financieros ef ON df.id_estado = ef.id_estado
                WHERE ef.id_empresa = :id_empresa
                GROUP BY ef.anio";

        $query = $this->pdo->prepare($sql);
        $query->bindParam(':id_empresa', $id_empresa, PDO::PARAM_INT);
        $query->execute();
        $resultados = $query->fetchAll(PDO::FETCH_ASSOC);

        // Calcular la prueba ácida para cada año
        $ratiosPorAno = [];
        foreach ($resultados as $resultado) {
            $activo_corriente = $resultado['activo_corriente'];
            $pasivo_corriente = $resultado['pasivo_corriente'];
            $inventario = $resultado['inventario'];

            // Calcular la prueba ácida y evitar división por cero
            $prueba_acida = ($pasivo_corriente > 0) ? ($activo_corriente - $inventario) / $pasivo_corriente : null;

            $ratiosPorAno[$resultado['anio']] = [
                'prueba_acida' => $prueba_acida
            ];
        }

        return $ratiosPorAno;
    }
    // Función para calcular el Capital de Trabajo
    public function calcularCapitalTrabajoPorAno($id_empresa)
    {
        // Consulta SQL para obtener saldos de activos corrientes y pasivos corrientes agrupados por año
        $sql = "SELECT ef.anio, 
                       SUM(CASE WHEN cl.nombre_clasificacion = 'Activo' AND tc.nombre_tipo = 'Corriente' THEN df.saldo ELSE 0 END) AS activo_corriente,
                       SUM(CASE WHEN cl.nombre_clasificacion = 'Pasivo' AND tc.nombre_tipo = 'Corriente' THEN df.saldo ELSE 0 END) AS pasivo_corriente
                FROM detalle_estado_financiero df
                INNER JOIN cuentas c ON df.id_cuenta = c.id_cuenta
                INNER JOIN clasificaciones cl ON c.id_clasificacion = cl.id_clasificacion
                INNER JOIN tipos_cuenta tc ON c.id_tipo = tc.id_tipo
                INNER JOIN estados_financieros ef ON df.id_estado = ef.id_estado
                WHERE ef.id_empresa = :id_empresa
                GROUP BY ef.anio";

        $query = $this->pdo->prepare($sql);
        $query->bindParam(':id_empresa', $id_empresa, PDO::PARAM_INT);
        $query->execute();
        $resultados = $query->fetchAll(PDO::FETCH_ASSOC);

        // Calcular el capital de trabajo para cada año
        $ratiosPorAno = [];
        foreach ($resultados as $resultado) {
            $activo_corriente = $resultado['activo_corriente'];
            $pasivo_corriente = $resultado['pasivo_corriente'];
            $capital_trabajo = $activo_corriente - $pasivo_corriente;

            $ratiosPorAno[$resultado['anio']] = [
                'capital_trabajo' => $capital_trabajo
            ];
        }

        return $ratiosPorAno;
    }
    // Función para calcular la Razón de Capital de Trabajo
    public function calcularRazonCapitalTrabajoPorAno($id_empresa)
    {
        // Consulta SQL para obtener los saldos de activos corrientes, pasivos corrientes y activos totales por año
        $sql = "SELECT ef.anio, 
                       SUM(CASE WHEN cl.nombre_clasificacion = 'Activo' AND tc.nombre_tipo = 'Corriente' THEN df.saldo ELSE 0 END) AS activo_corriente,
                       SUM(CASE WHEN cl.nombre_clasificacion = 'Pasivo' AND tc.nombre_tipo = 'Corriente' THEN df.saldo ELSE 0 END) AS pasivo_corriente,
                       SUM(CASE WHEN cl.nombre_clasificacion = 'Activo' THEN df.saldo ELSE 0 END) AS activos_totales
                FROM detalle_estado_financiero df
                INNER JOIN cuentas c ON df.id_cuenta = c.id_cuenta
                INNER JOIN clasificaciones cl ON c.id_clasificacion = cl.id_clasificacion
                INNER JOIN tipos_cuenta tc ON c.id_tipo = tc.id_tipo
                INNER JOIN estados_financieros ef ON df.id_estado = ef.id_estado
                WHERE ef.id_empresa = :id_empresa
                GROUP BY ef.anio";

        $query = $this->pdo->prepare($sql);
        $query->bindParam(':id_empresa', $id_empresa, PDO::PARAM_INT);
        $query->execute();
        $resultados = $query->fetchAll(PDO::FETCH_ASSOC);

        // Calcular la razón de capital de trabajo para cada año
        $ratiosPorAno = [];
        foreach ($resultados as $resultado) {
            $activo_corriente = $resultado['activo_corriente'];
            $pasivo_corriente = $resultado['pasivo_corriente'];
            $activos_totales = $resultado['activos_totales'];

            // Calcular la razón de capital de trabajo y evitar división por cero
            $razon_capital_trabajo = ($activos_totales > 0) ? ($activo_corriente - $pasivo_corriente) / $activos_totales : null;

            $ratiosPorAno[$resultado['anio']] = [
                'razon_capital_trabajo' => $razon_capital_trabajo
            ];
        }

        return $ratiosPorAno;
    }
    // Función para calcular el Grado de Endeudamiento
    public function calcularGradoEndeudamientoPorAno($id_empresa)
    {
        // Consulta SQL para obtener activos y pasivos totales por año
        $sql = "SELECT ef.anio, 
                       SUM(CASE WHEN cl.nombre_clasificacion = 'Activo' THEN df.saldo ELSE 0 END) AS activo_total,
                       SUM(CASE WHEN cl.nombre_clasificacion = 'Pasivo' THEN df.saldo ELSE 0 END) AS pasivo_total
                FROM detalle_estado_financiero df
                INNER JOIN cuentas c ON df.id_cuenta = c.id_cuenta
                INNER JOIN clasificaciones cl ON c.id_clasificacion = cl.id_clasificacion
                INNER JOIN estados_financieros ef ON df.id_estado = ef.id_estado
                WHERE ef.id_empresa = :id_empresa
                GROUP BY ef.anio";

        $query = $this->pdo->prepare($sql);
        $query->bindParam(':id_empresa', $id_empresa, PDO::PARAM_INT);
        $query->execute();
        $resultados = $query->fetchAll(PDO::FETCH_ASSOC);

        // Calcular el grado de endeudamiento para cada año
        $ratiosPorAno = [];
        foreach ($resultados as $resultado) {
            $activo_total = $resultado['activo_total'];
            $pasivo_total = $resultado['pasivo_total'];
            $grado_endeudamiento = $activo_total > 0 ? $pasivo_total / $activo_total : null;

            $ratiosPorAno[$resultado['anio']] = [
                'grado_endeudamiento' => $grado_endeudamiento
            ];
        }

        return $ratiosPorAno;
    }
    // Función para calcular el Grado de Propiedad
    public function calcularGradoPropiedadPorAno($id_empresa)
    {
        // Consulta SQL para obtener patrimonio y activos totales por año
        $sql = "SELECT ef.anio, 
                       SUM(CASE WHEN cl.nombre_clasificacion = 'Patrimonio' THEN df.saldo ELSE 0 END) AS patrimonio_total,
                       SUM(CASE WHEN cl.nombre_clasificacion = 'Activo' THEN df.saldo ELSE 0 END) AS activo_total
                FROM detalle_estado_financiero df
                INNER JOIN cuentas c ON df.id_cuenta = c.id_cuenta
                INNER JOIN clasificaciones cl ON c.id_clasificacion = cl.id_clasificacion
                INNER JOIN estados_financieros ef ON df.id_estado = ef.id_estado
                WHERE ef.id_empresa = :id_empresa
                GROUP BY ef.anio";

        $query = $this->pdo->prepare($sql);
        $query->bindParam(':id_empresa', $id_empresa, PDO::PARAM_INT);
        $query->execute();
        $resultados = $query->fetchAll(PDO::FETCH_ASSOC);

        // Calcular el grado de propiedad para cada año
        $ratiosPorAno = [];
        foreach ($resultados as $resultado) {
            $patrimonio_total = $resultado['patrimonio_total'];
            $activo_total = $resultado['activo_total'];
            $grado_propiedad = $activo_total > 0 ? $patrimonio_total / $activo_total : null;

            $ratiosPorAno[$resultado['anio']] = [
                'grado_propiedad' => $grado_propiedad
            ];
        }

        return $ratiosPorAno;
    }
    // Función para calcular la Razón de Endeudamiento Patrimonial
    public function calcularRazonEndeudamientoPatrimonialPorAno($id_empresa)
    {
        // Consulta SQL para obtener pasivo total y patrimonio total por año
        $sql = "SELECT ef.anio, 
                       SUM(CASE WHEN cl.nombre_clasificacion = 'Pasivo' THEN df.saldo ELSE 0 END) AS pasivo_total,
                       SUM(CASE WHEN cl.nombre_clasificacion = 'Patrimonio' THEN df.saldo ELSE 0 END) AS patrimonio_total
                FROM detalle_estado_financiero df
                INNER JOIN cuentas c ON df.id_cuenta = c.id_cuenta
                INNER JOIN clasificaciones cl ON c.id_clasificacion = cl.id_clasificacion
                INNER JOIN estados_financieros ef ON df.id_estado = ef.id_estado
                WHERE ef.id_empresa = :id_empresa
                GROUP BY ef.anio";

        $query = $this->pdo->prepare($sql);
        $query->bindParam(':id_empresa', $id_empresa, PDO::PARAM_INT);
        $query->execute();
        $resultados = $query->fetchAll(PDO::FETCH_ASSOC);

        // Calcular la razón de endeudamiento patrimonial para cada año
        $ratiosPorAno = [];
        foreach ($resultados as $resultado) {
            $pasivo_total = $resultado['pasivo_total'];
            $patrimonio_total = $resultado['patrimonio_total'];
            $razon_endeudamiento_patrimonial = $patrimonio_total > 0 ? $pasivo_total / $patrimonio_total : null;

            $ratiosPorAno[$resultado['anio']] = [
                'razon_endeudamiento_patrimonial' => $razon_endeudamiento_patrimonial
            ];
        }

        return $ratiosPorAno;
    }
    // Funciónes para calcular la Rentabilidad sobre Activos (ROA) Utilidad Neta / Activos Totales
    // y la Rentabilidad sobre Patrimonio (ROE) Utilidad Neta / Patrimonio Total
    public function calcularUtilidadesPorAno($id_empresa)
    {
        // Consulta SQL para obtener ingresos y costos/gastos operacionales y no operacionales agrupados por año
        $sql = "SELECT ef.anio,
                       SUM(CASE WHEN cl.nombre_clasificacion = 'Ingreso' AND tc.nombre_tipo = 'Operación' THEN df.saldo ELSE 0 END) AS ingresos_operacion,
                       SUM(CASE WHEN cl.nombre_clasificacion = 'Costo y Gasto' AND tc.nombre_tipo = 'Operación' THEN df.saldo ELSE 0 END) AS costos_gastos_operacion,
                       SUM(CASE WHEN cl.nombre_clasificacion = 'Ingreso' AND tc.nombre_tipo = 'No Operación' THEN df.saldo ELSE 0 END) AS ingresos_no_operacion,
                       SUM(CASE WHEN cl.nombre_clasificacion = 'Costo y Gasto' AND tc.nombre_tipo = 'No Operación' THEN df.saldo ELSE 0 END) AS costos_gastos_no_operacion
                FROM detalle_estado_financiero df
                INNER JOIN cuentas c ON df.id_cuenta = c.id_cuenta
                INNER JOIN clasificaciones cl ON c.id_clasificacion = cl.id_clasificacion
                INNER JOIN tipos_cuenta tc ON c.id_tipo = tc.id_tipo
                INNER JOIN estados_financieros ef ON df.id_estado = ef.id_estado
                WHERE ef.id_empresa = :id_empresa
                GROUP BY ef.anio";

        $query = $this->pdo->prepare($sql);
        $query->bindParam(':id_empresa', $id_empresa, PDO::PARAM_INT);
        $query->execute();
        $resultados = $query->fetchAll(PDO::FETCH_ASSOC);

        // Calcular Utilidad de Operación y Utilidad Neta para cada año
        $utilidadesPorAno = [];
        foreach ($resultados as $resultado) {
            $ingresos_operacion = $resultado['ingresos_operacion'];
            $costos_gastos_operacion = $resultado['costos_gastos_operacion'];
            $ingresos_no_operacion = $resultado['ingresos_no_operacion'];
            $costos_gastos_no_operacion = $resultado['costos_gastos_no_operacion'];

            // Calcula la Utilidad de Operación
            $utilidad_operacion = $ingresos_operacion - $costos_gastos_operacion;

            // Calcula la Utilidad Neta
            $utilidad_neta = $utilidad_operacion + $ingresos_no_operacion - $costos_gastos_no_operacion;

            $utilidadesPorAno[$resultado['anio']] = [
                'utilidad_operacion' => $utilidad_operacion,
                'utilidad_neta' => $utilidad_neta,
                'ingresos_operacion' => $ingresos_operacion, // Asegurarse de que ingresos_operacion esté disponible
                'costos_gastos_operacion' => $costos_gastos_operacion // Asegurarse de que costos_gastos_operacion esté disponible
            ];
        }

        return $utilidadesPorAno;
    }
    // Función para obtener los Activos Totales por Año
    public function obtenerActivosTotalesPorAno($id_empresa, $anio)
    {
        $sql = "SELECT SUM(df.saldo) AS activos_totales
            FROM detalle_estado_financiero df
            INNER JOIN cuentas c ON df.id_cuenta = c.id_cuenta
            INNER JOIN clasificaciones cl ON c.id_clasificacion = cl.id_clasificacion
            INNER JOIN estados_financieros ef ON df.id_estado = ef.id_estado
            WHERE ef.id_empresa = :id_empresa
              AND ef.anio = :anio
              AND cl.nombre_clasificacion = 'Activo'"; // Filtra por nombre_clasificacion en lugar del ID

        $query = $this->pdo->prepare($sql);
        $query->bindParam(':id_empresa', $id_empresa, PDO::PARAM_INT);
        $query->bindParam(':anio', $anio, PDO::PARAM_INT);
        $query->execute();

        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['activos_totales'] : 0;
    }

    // Función para obtener el Patrimonio Total por Año
    public function obtenerPatrimonioTotalPorAno($id_empresa, $anio)
    {
        $sql = "SELECT SUM(df.saldo) AS patrimonio_total
            FROM detalle_estado_financiero df
            INNER JOIN cuentas c ON df.id_cuenta = c.id_cuenta
            INNER JOIN clasificaciones cl ON c.id_clasificacion = cl.id_clasificacion
            INNER JOIN estados_financieros ef ON df.id_estado = ef.id_estado
            WHERE ef.id_empresa = :id_empresa
              AND ef.anio = :anio
              AND cl.nombre_clasificacion = 'Patrimonio'"; // Filtra por el nombre "Patrimonio"

        $query = $this->pdo->prepare($sql);
        $query->bindParam(':id_empresa', $id_empresa, PDO::PARAM_INT);
        $query->bindParam(':anio', $anio, PDO::PARAM_INT);
        $query->execute();

        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['patrimonio_total'] : 0;
    }
    // Función para guardar los ratios en la base de datos
    public function guardarRatiosPorAno($id_empresa, $anio, $ratios)
    {
        // Verificar si los valores en $ratios son válidos (números o null)
        foreach ($ratios as $key => $value) {
            if (is_array($value)) {
                throw new Exception("El valor de $key en ratios no puede ser un array");
            }
        }

        // SQL para verificar si ya existen registros para el año y la empresa
        $sqlSelect = "SELECT * FROM ratios_financieros WHERE id_empresa = :id_empresa AND anio = :anio";
        $querySelect = $this->pdo->prepare($sqlSelect);
        $querySelect->execute([':id_empresa' => $id_empresa, ':anio' => $anio]);
        $existe = $querySelect->fetch();

        if ($existe) {
            // Actualiza los ratios existentes
            $sqlUpdate = "UPDATE ratios_financieros SET 
                        liquidez_corriente = :liquidez_corriente,
                        prueba_acida = :prueba_acida,
                        capital_trabajo = :capital_trabajo,
                        razon_capital_trabajo = :razon_capital_trabajo,
                        grado_endeudamiento = :grado_endeudamiento,
                        grado_propiedad = :grado_propiedad,
                        razon_endeudamiento_patrimonial = :razon_endeudamiento_patrimonial,
                        roa = :roa,
                        roe = :roe,
                        margen_neto = :margen_neto,
                        indice_eficiencia_operativa = :indice_eficiencia_operativa,
                        updated_at = NOW()
                    WHERE id_empresa = :id_empresa AND anio = :anio";
            $queryUpdate = $this->pdo->prepare($sqlUpdate);
            $queryUpdate->execute(array_merge([':id_empresa' => $id_empresa, ':anio' => $anio], $ratios));
        } else {
            // Inserta nuevos ratios
            $sqlInsert = "INSERT INTO ratios_financieros (id_empresa, anio, liquidez_corriente, prueba_acida, capital_trabajo, razon_capital_trabajo, grado_endeudamiento, grado_propiedad, razon_endeudamiento_patrimonial, roa, roe, margen_neto, indice_eficiencia_operativa, created_at) 
                      VALUES (:id_empresa, :anio, :liquidez_corriente, :prueba_acida, :capital_trabajo, :razon_capital_trabajo, :grado_endeudamiento, :grado_propiedad, :razon_endeudamiento_patrimonial, :roa, :roe, :margen_neto, :indice_eficiencia_operativa, NOW())";
            $queryInsert = $this->pdo->prepare($sqlInsert);
            $queryInsert->execute(array_merge([':id_empresa' => $id_empresa, ':anio' => $anio], $ratios));
        }
    }
    // Función para calcular y guardar todos los ratios para todos los años
    public function calcularYGuardarRatiosPorAno($id_empresa)
    {
        // Calcula todos los ratios
        $liquidezCorriente = $this->calcularLiquidezCorrientePorAno($id_empresa);
        $pruebaAcida = $this->calcularPruebaAcidaPorAno($id_empresa);
        $capitalTrabajo = $this->calcularCapitalTrabajoPorAno($id_empresa);
        $razonCapitalTrabajo = $this->calcularRazonCapitalTrabajoPorAno($id_empresa);
        $gradoEndeudamiento = $this->calcularGradoEndeudamientoPorAno($id_empresa);
        $gradoPropiedad = $this->calcularGradoPropiedadPorAno($id_empresa);
        $razonEndeudamientoPatrimonial = $this->calcularRazonEndeudamientoPatrimonialPorAno($id_empresa);
        $utilidades = $this->calcularUtilidadesPorAno($id_empresa);

        // Calcula ratios adicionales: ROA, ROE, Margen Neto, Índice de Eficiencia Operativa
        foreach ($utilidades as $anio => $data) {
            $ratios = [
                'liquidez_corriente' => $liquidezCorriente[$anio]['liquidez_corriente'] ?? null,
                'prueba_acida' => $pruebaAcida[$anio]['prueba_acida'] ?? null,
                'capital_trabajo' => $capitalTrabajo[$anio]['capital_trabajo'] ?? null,
                'razon_capital_trabajo' => $razonCapitalTrabajo[$anio]['razon_capital_trabajo'] ?? null,
                'grado_endeudamiento' => $gradoEndeudamiento[$anio]['grado_endeudamiento'] ?? null,
                'grado_propiedad' => $gradoPropiedad[$anio]['grado_propiedad'] ?? null,
                'razon_endeudamiento_patrimonial' => $razonEndeudamientoPatrimonial[$anio]['razon_endeudamiento_patrimonial'] ?? null,
                'roa' => isset($data['utilidad_neta']) && $this->obtenerActivosTotalesPorAno($id_empresa, $anio) > 0 ? $data['utilidad_neta'] / $this->obtenerActivosTotalesPorAno($id_empresa, $anio) : null,
                'roe' => isset($data['utilidad_neta']) && $this->obtenerPatrimonioTotalPorAno($id_empresa, $anio) > 0 ? $data['utilidad_neta'] / $this->obtenerPatrimonioTotalPorAno($id_empresa, $anio) : null,
                'margen_neto' => isset($data['ingresos_operacion']) && $data['ingresos_operacion'] > 0 ? $data['utilidad_neta'] / $data['ingresos_operacion'] : null,
                'indice_eficiencia_operativa' => isset($data['ingresos_operacion']) && $data['ingresos_operacion'] > 0 ? $data['costos_gastos_operacion'] / $data['ingresos_operacion'] : null,
            ];

            // Guarda los ratios para el año actual
            $this->guardarRatiosPorAno($id_empresa, $anio, $ratios);
        }
    }
}
