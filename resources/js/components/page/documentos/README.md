# Componentes de Documentos

Este directorio contiene los componentes Vue.js para la gestión de documentos en la aplicación NOTARYNET.

## Componentes

### 1. index.vue
Componente principal que muestra la vista completa de documentos. Incluye:
- Lista de documentos con filtros y búsqueda
- Estadísticas de documentos
- Paginación
- Modales para ver y confirmar acciones

### 2. DocumentFilter.vue
Componente para filtros y búsqueda de documentos:
- Campo de búsqueda con icono
- Botones de filtro por estado (Todos, Firmados, Pendientes, Cancelados)
- Emite eventos para comunicación con el componente padre

### 3. DocumentCard.vue
Componente para mostrar una tarjeta individual de documento:
- Información del documento (título, descripción, fechas)
- Estado visual con colores distintivos
- Botones de acción (Ver, Firmar, Descargar, Cancelar)
- Emite eventos para las acciones del usuario

### 4. DocumentStats.vue
Componente para mostrar estadísticas de documentos:
- Contador total de documentos
- Contadores por estado (Firmados, Pendientes, Cancelados)
- Iconos y colores distintivos para cada estado

## Características

- **Responsive**: Todos los componentes están optimizados para dispositivos móviles
- **Internacionalización**: Soporte completo para español e inglés
- **Accesibilidad**: Uso de iconos FontAwesome y estructura semántica
- **Modularidad**: Componentes reutilizables y bien estructurados
- **Estados visuales**: Diferentes colores y estilos según el estado del documento

## Uso

```vue
<template>
  <div>
    <!-- Componente principal -->
    <DocumentsPage />
    
    <!-- O usar componentes individuales -->
    <DocumentFilter 
      v-model="searchQuery"
      :filter="activeFilter"
      @search="handleSearch"
      @filter-change="handleFilterChange"
    />
    
    <DocumentStats :documents="documents" />
    
    <DocumentCard 
      :document="document"
      @view="handleView"
      @sign="handleSign"
      @cancel="handleCancel"
    />
  </div>
</template>
```

## Traducciones

Las traducciones están definidas en `resources/js/langs.js` bajo la sección `documents`:

- `title`: Título de la página
- `subtitle`: Subtítulo descriptivo
- `search_placeholder`: Placeholder del campo de búsqueda
- `all_documents`, `signed`, `pending`, `cancelled`: Estados de documentos
- `view`, `sign`, `download`, `cancel`: Acciones disponibles
- Y muchas más...

## Estilos

Los componentes utilizan:
- Bootstrap 4 para la estructura base
- CSS personalizado para estilos específicos
- FontAwesome para iconos
- Colores consistentes con el diseño de la aplicación

## Integración

Los componentes están integrados con:
- Vue Router para navegación
- Axios para llamadas a la API
- Vue i18n para internacionalización
- Bootstrap Vue para componentes UI
