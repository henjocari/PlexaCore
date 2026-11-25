<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SilogtranPqrs extends Model
{
    protected $table = 'silogtran_pqrs';

    protected $fillable = [
        'radicado',
        'nit_cliente',
        'tipo',
        'categoria',
        'prioridad',
        'nombre',
        'tipo_documento',
        'documento',
        'email',
        'telefono',
        'pais',
        'departamento',
        'ciudad',
        'direccion',
        'producto_servicio',
        'manifiesto',
        'asunto',
        'descripcion',
        'adjunto',
        'estado',
        'respuesta',
        'user_id',
    ];

    public const TIPOS = ['Peticion', 'Queja', 'Reclamo', 'Sugerencia'];

    public const CATEGORIAS = [
        'Operación de transporte',
        'Manifiestos',
        'Facturación',
        'Conductores',
        'Vehículos',
        'Plataforma Silogtran',
        'Otro',
    ];

    public const ESTADOS = ['Radicado', 'En Gestion', 'Cerrado'];

    // "Producto o Servicio" (solicitado por Servicio al Cliente)
    public const PRODUCTOS = [
        'Comercialización GLP',
        'Comercialización Gas Natural',
        'Transporte GLP',
        'Transporte de Hidrocarburos',
        'Operaciones en Planta',
        'Administrativos',
        'Cotizaciones de servicios',
    ];

    // Países del formulario (Colombia activa las listas de departamento y municipio)
    public const PAISES = ['Colombia', 'Venezuela', 'Ecuador', 'Perú', 'Panamá', 'Brasil', 'México', 'Estados Unidos', 'España', 'Otro país'];

    /** Departamento => [municipios] (DIVIPOLA, public/data/colombia-municipios.json). */
    public static function municipiosColombia(): array
    {
        static $datos = null;
        if ($datos === null) {
            $ruta = public_path('data/colombia-municipios.json');
            $datos = is_file($ruta) ? (json_decode((string) file_get_contents($ruta), true) ?: []) : [];
        }
        return $datos;
    }

    // Departamentos de Colombia (sugerencias del formulario; se acepta texto libre para otros países)
    public const DEPARTAMENTOS_CO = [
        'Amazonas', 'Antioquia', 'Arauca', 'Atlántico', 'Bogotá D.C.', 'Bolívar', 'Boyacá', 'Caldas',
        'Caquetá', 'Casanare', 'Cauca', 'Cesar', 'Chocó', 'Córdoba', 'Cundinamarca', 'Guainía',
        'Guaviare', 'Huila', 'La Guajira', 'Magdalena', 'Meta', 'Nariño', 'Norte de Santander',
        'Putumayo', 'Quindío', 'Risaralda', 'San Andrés y Providencia', 'Santander', 'Sucre',
        'Tolima', 'Valle del Cauca', 'Vaupés', 'Vichada',
    ];
}
