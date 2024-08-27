import pandas as pd
import json
import sys

def transformar_datos(config, provider, df_proveedor):
    # Mapeo de datos utilizando la configuración
    mapping = config.get(provider, {})
    print(f"Mapeo de datos: {mapping}")

    # Verificar si las columnas mapeadas existen en el DataFrame
    missing_columns = [col for col in mapping.values() if col not in df_proveedor.columns]
    if missing_columns:
        print(f"Error: Las siguientes columnas faltan en el archivo Excel: {missing_columns}")
        sys.exit(1)

    with open('contador_id_global.txt', 'r') as id_file:
        global_id = int(id_file.read().strip())

    df_transformado = pd.DataFrame()

    df_transformado['producto_id'] = range(global_id + 1, global_id + 1 + len(df_proveedor))
    df_transformado['producto_codigo'] = df_proveedor.get(mapping.get('producto_codigo'), None)
    df_transformado['producto_nombre'] = df_proveedor[mapping['producto_nombre']]
    df_transformado['producto_stock_total'] = 100
    df_transformado['producto_tipo_unidad'] = provider
    df_transformado['producto_precio_compra'] = df_proveedor[mapping['producto_precio_compra']]
    df_transformado['producto_precio_venta'] = df_proveedor[mapping['producto_precio_compra']]
    df_transformado['producto_marca'] = None
    df_transformado['producto_modelo'] = None
    df_transformado['producto_estado'] = 'Habilitado'
    df_transformado['producto_foto'] = None
    df_transformado['categoria_id'] = 2

    # Guardar el archivo convertido como CSV
    df_transformado.to_csv('uploads/convertido.csv', index=False)

    with open('contador_id_global.txt', 'w') as id_file:
        id_file.write(str(global_id + len(df_proveedor)))

    print("Archivo convertido generado correctamente.")

with open('config.json', 'r', encoding='utf-8') as file:
    config = json.load(file)

# Obtener los parámetros
provider = sys.argv[1]
input_file = sys.argv[2]
output_file = sys.argv[3]

print(f"Proveedor: {provider}")
print(f"Archivo de entrada: {input_file}")
print(f"Archivo de salida: {output_file}")

if provider == "celeste_y_blanca":
    df_proveedor = pd.read_excel(input_file, header=7)
    df_proveedor = df_proveedor.dropna(how='all')
    df_proveedor = df_proveedor.reset_index(drop=True)
    transformar_datos(config,provider,df_proveedor)
elif provider == "di_paolo_mayorista":
    df_proveedor = pd.read_excel(input_file, header=6)
    transformar_datos(config,provider,df_proveedor)
elif provider == "marplast":
    df_proveedor = pd.read_excel(input_file, header=9)
    transformar_datos(config,provider,df_proveedor)
else:
    df_proveedor = pd.read_excel(input_file)
    transformar_datos(config,provider,df_proveedor)

print("Nombres de columnas en el archivo Excel:", df_proveedor.columns.tolist())
