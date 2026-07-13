=== Plogins Locator - Store Locator for WooCommerce ===
Contributors: motylanogha
Tags: woocommerce, store locator, store finder, locations, shortcode
Requires at least: 6.5
Tested up to: 7.0
Requires PHP: 8.1
Stable tag: 1.0.2
Requires Plugins: woocommerce
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Muestra las ubicaciones de tus tiendas físicas con una lista accesible y con capacidad de búsqueda que los clientes pueden filtrar por ciudad, código postal o nombre.

== Description ==

Locator enumera tus tiendas físicas en la tienda. Introduces cada ubicación una vez
en wp-admin (nombre, dirección, ciudad, código postal, país, teléfono y horario de atención), luego
añades el shortcode `[locator]` a cualquier página para imprimir un directorio con capacidad de búsqueda.

No hay mapa ni servicio externo. Sin clave de Google Maps, sin llamada a la API, sin
script de seguimiento. Cada ubicación se imprime en el HTML de la página, por lo que el directorio es
visible incluso con JavaScript desactivado. Cuando se ejecuta JavaScript, el cuadro de búsqueda oculta
y muestra tarjetas a medida que el visitante escribe, filtrando por ciudad, código postal o nombre de la tienda
íntegramente en el navegador.

Código fuente e informes de errores: https://github.com/wppoland/plogins-locator

<strong>Características</strong>

* Las ubicaciones de las tiendas aparecen como su propio tipo de publicación en el menú de WooCommerce.
* Cada ubicación mantiene su dirección, ciudad, código postal, país, teléfono y horario de atención.
* El shortcode `[locator]` representa el directorio; puedes dejar el cuadro de búsqueda desactivado si solo tienes un par de tiendas.
* La búsqueda se ejecuta en el lado del cliente mediante nombre, dirección, ciudad, código postal y país. No se envía ninguna solicitud mientras se escribe.
* La visualización por tarjeta alterna entre dirección, horario de apertura y teléfono (el nombre de la tienda siempre se muestra).
* El recuento de resultados se anuncia a través de una región en vivo de ARIA, el campo de búsqueda se puede operar con el teclado y las tarjetas usan contornos visibles.
* La hoja de estilo y el script se cargan solo en las páginas donde el shortcode realmente se renderizó y el marcado evita el cambio de diseño.
* Los estilos de la tienda siguen la preferencia de modo claro/oscuro del visitante y respetan la preferencia de movimiento reducido (prefers-reduced-motion).

== Installation ==

1. Sube el complemento a `/wp-content/plugins/locator`, o instálalo a través de Complementos → Añadir nuevo.
2. Actívalo. WooCommerce debe estar activo.
3. Ve a WooCommerce → Ubicaciones de tiendas y añade tus tiendas.
4. Configura WooCommerce → Localizador de tiendas (cuadro de búsqueda y campos visibles).
5. Añade el shortcode `[locator]` a cualquier página.

== Frequently Asked Questions ==

= Documentation and links =

* <strong>Documentación</strong> - https://plogins.com/es/plogins-locator/docs/
* <strong>Página de complementos</strong> - https://plogins.com/es/plogins-locator/
* <strong>Código fuente</strong> - https://github.com/wppoland/plogins-locator
* <strong>Informes de errores y solicitudes de funciones</strong> - https://github.com/wppoland/plogins-locator/issues


= Does it require WooCommerce? =

Sí. Locator se registra en el menú de WooCommerce y requiere que WooCommerce esté activo.

= Does it show a map? =

No. Locator muestra una lista de tarjetas con capacidad de búsqueda, no un mapa, por lo que no necesita clave de mapa ni servicio externo. Aún puedes almacenar la latitud y longitud por ubicación para usarlas con un complemento.

= Which details can I show on each card? =

El nombre de la tienda siempre se muestra. Puedes alternar dirección, horario de apertura y teléfono en los ajustes.

= How does the search box work? =

El shortcode `[locator]` filtra las tarjetas de ubicación del lado del cliente a medida que el comprador escribe.

= Can I add stores without custom code? =

Sí. Añade ubicaciones en <strong>WooCommerce → Ubicaciones de tiendas</strong> y coloca `[locator]` en cualquier página.


= Does this plugin work on WordPress Multisite? =

Sí. Este complemento es compatible con WordPress Multisite. Actívalo en red o en sitios concretos; cada sitio mantiene sus propios ajustes y datos.

== Screenshots ==

1. El directorio de la tienda con capacidad de búsqueda.
2. La página de configuración del Localizador de tiendas.

== External Services ==

Locator no se conecta a ningún servicio externo. No registra ninguna API remota, no envía ninguna solicitud HTTP y no carga ningún script, fuente, mapa o mosaico de terceros. Los datos de tu tienda nunca abandonan tu sitio.

Cada ubicación se almacena en tu propio servidor como una publicación `locator_store`, con su dirección, ciudad, código postal, país, teléfono, horario de apertura, correo electrónico y cualquier latitud/longitud guardada en los metadatos de esa publicación. Los ajustes se encuentran en la opción `locator_settings`. Las coordenadas se introducen a mano en la pantalla de ubicación; nada se geocodifica con respecto a un proveedor externo. La búsqueda de la tienda filtra las tarjetas en el navegador del visitante, por lo que no se realiza ninguna solicitud mientras se escribe y el complemento no envía ningún correo electrónico.

== Translations ==

Plogins Locator incluye traducciones al polaco, alemán y español para la interfaz del complemento. El dominio de texto es `plogins-locator`, por lo que los paquetes de idioma de WordPress.org también pueden anular o ampliar estas traducciones empaquetadas.

== Changelog ==

= 1.0.2 =
* Se añadieron traducciones integradas en polaco, alemán y español para la interfaz del complemento.

= 1.0.1 =
* Primera versión estable.

= 0.1.4 =
* Renombrado a Plogins Locator for WooCommerce para un nombre de complemento más distintivo.

= 0.1.3 =
* Solución: las ubicaciones de las tiendas ya no asumen la capacidad `manage_woocommerce`. El registro del tipo de publicación de ubicaciones asignaba las meta capabilities a `manage_woocommerce`, lo que hacía que todas las comprobaciones de `manage_woocommerce` fallaran mientras el complemento estaba activo, ocultando todo el menú de WooCommerce en la administración (Ajustes, Estado, Pedidos) y la propia página de ajustes del complemento.

= 0.1.2 =
* Importación programática de la tienda a través de `StoreWriter` con el filtro `locator/import_store_fields` y la acción `locator/store_imported`.

= 0.1.1 =
* Añade el filtro `locator/store_groups` para que los complementos puedan agrupar el directorio de la tienda por región o país.

= 0.1.0 =
* Lanzamiento inicial: tipo de publicación de ubicación de tienda, shortcode [locator], lista de búsqueda, ajustes.
