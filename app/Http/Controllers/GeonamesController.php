<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GeonamesController extends Controller
{
    private const CACHE_TTL = 3600; // 1 hora en segundos
    private const BASE_URL = 'http://api.geonames.org'; // Cambiado a http y api en lugar de secure

    private function getUsername()
    {
        return config('services.geonames.username', 'camilogomez666');
    }

    private function logError($message, $context = [])
    {
        Log::error($message, array_merge([
            'username' => $this->getUsername(),
            'timestamp' => now()->toIso8601String()
        ], $context));
    }

    private function testConnection()
    {
        try {
            // Primero probamos una petición simple a la API
            $testUrl = 'http://api.geonames.org/postalCodeLookupJSON';
            $testParams = [
                'postalcode' => '1000',
                'country' => 'CH',
                'username' => $this->getUsername()
            ];

            Log::info('Probando conexión básica con Geonames', [
                'url' => $testUrl,
                'params' => $testParams
            ]);

            $response = Http::timeout(5)->get($testUrl, $testParams);
            
            Log::info('Respuesta de prueba', [
                'status' => $response->status(),
                'headers' => $response->headers(),
                'body' => $response->body()
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['status'])) {
                    Log::error('Error en respuesta de prueba', [
                        'status' => $data['status']
                    ]);
                    return false;
                }
                return true;
            }

            return false;
        } catch (\Exception $e) {
            Log::error('Error en prueba de conexión', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    public function checkAccountStatus()
    {
        try {
            Log::info('Iniciando verificación de cuenta Geonames');
            
            // Primero probamos la conexión básica
            if (!$this->testConnection()) {
                Log::error('Fallo en prueba de conexión básica');
                return response()->json([
                    'error' => 'No se pudo conectar con Geonames',
                    'details' => 'La conexión básica falló. Por favor, verifica tu conexión a internet y que la API de Geonames esté disponible.'
                ], 500);
            }

            // Si la conexión básica funciona, probamos con una petición más específica
            $response = Http::timeout(5)->get('http://api.geonames.org/countryInfoJSON', [
                'country' => 'CO',
                'username' => $this->getUsername(),
                'lang' => 'es'
            ]);

            Log::info('Respuesta de verificación de cuenta', [
                'status' => $response->status(),
                'headers' => $response->headers(),
                'body' => $response->body()
            ]);

            if (!$response->successful()) {
                Log::error('Error en verificación de cuenta', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return response()->json([
                    'error' => 'Error en la verificación de cuenta',
                    'details' => 'La API respondió con un error. Por favor, verifica que tu cuenta esté activa.'
                ], 500);
            }

            $data = $response->json();
            
            if (isset($data['status'])) {
                Log::error('Error en respuesta de Geonames', [
                    'status' => $data['status']
                ]);
                return response()->json([
                    'error' => 'Error en cuenta Geonames',
                    'details' => $data['status']['message'] ?? 'Error desconocido en la cuenta'
                ], 500);
            }

            // Si llegamos aquí, la cuenta está funcionando
            Log::info('Cuenta Geonames verificada exitosamente');
            return response()->json([
                'status' => 'active',
                'message' => 'Cuenta verificada correctamente',
                'details' => [
                    'username' => $this->getUsername(),
                    'test_country' => 'Colombia',
                    'response' => $data
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Excepción en verificación de cuenta', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Error en verificación de cuenta',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    public function getCountries()
    {
        try {
            Log::info('Iniciando obtención de países desde Geonames');
            
            // Intentar obtener del caché primero
            $cachedCountries = Cache::get('geonames_countries');
            if ($cachedCountries) {
                Log::info('Países obtenidos desde caché');
                return response()->json($cachedCountries);
            }

            // Si no está en caché, hacer la petición a Geonames
            $url = self::BASE_URL . '/countryInfoJSON';
            $params = [
                'username' => $this->getUsername(),
                'lang' => 'es'
            ];

            Log::info('Realizando petición a Geonames', [
                'url' => $url,
                'params' => $params
            ]);

            $response = Http::timeout(10)->get($url, $params);
            
            Log::info('Respuesta de Geonames', [
                'status' => $response->status(),
                'headers' => $response->headers(),
                'body' => $response->body()
            ]);

            if (!$response->successful()) {
                throw new \Exception('Error en la respuesta de Geonames: ' . $response->status());
            }

            $data = $response->json();
            
            if (isset($data['status'])) {
                throw new \Exception('Error de Geonames: ' . ($data['status']['message'] ?? 'Error desconocido'));
            }

            if (!isset($data['geonames']) || !is_array($data['geonames'])) {
                throw new \Exception('Formato de respuesta inválido de Geonames');
            }

            // Guardar en caché
            Cache::put('geonames_countries', $data, self::CACHE_TTL);
            
            Log::info('Países obtenidos exitosamente', [
                'count' => count($data['geonames'])
            ]);

            return response()->json($data);

        } catch (\Exception $e) {
            $this->logError('Error obteniendo países', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Intentar obtener una lista básica de países como respaldo
            try {
                Log::info('Intentando obtener lista básica de países como respaldo');
                $basicCountries = [
                    'geonames' => [
                        ['countryCode' => 'CO', 'countryName' => 'Colombia'],
                        ['countryCode' => 'VE', 'countryName' => 'Venezuela'],
                        ['countryCode' => 'EC', 'countryName' => 'Ecuador'],
                        ['countryCode' => 'PE', 'countryName' => 'Perú'],
                        ['countryCode' => 'CL', 'countryName' => 'Chile'],
                        ['countryCode' => 'AR', 'countryName' => 'Argentina'],
                        ['countryCode' => 'MX', 'countryName' => 'México'],
                        ['countryCode' => 'ES', 'countryName' => 'España'],
                        ['countryCode' => 'US', 'countryName' => 'Estados Unidos'],
                        ['countryCode' => 'CA', 'countryName' => 'Canadá']
                    ]
                ];
                
                Cache::put('geonames_countries', $basicCountries, self::CACHE_TTL);
                
                Log::info('Lista básica de países cargada como respaldo');
                return response()->json($basicCountries);
            } catch (\Exception $fallbackError) {
                $this->logError('Error en respaldo de países', [
                    'error' => $fallbackError->getMessage()
                ]);
                
                return response()->json([
                    'error' => 'Error obteniendo países',
                    'details' => $e->getMessage()
                ], 500);
            }
        }
    }

    public function getStates(Request $request)
    {
        try {
            Log::info('Iniciando obtención de estados desde Geonames', [
                'countryCode' => $request->input('countryCode')
            ]);

            $countryCode = $request->input('countryCode');
            if (!$countryCode) {
                throw new \Exception('Country code is required');
            }

            // Intentar obtener del caché primero
            $cacheKey = "geonames_states_{$countryCode}";
            $cachedStates = Cache::get($cacheKey);
            if ($cachedStates) {
                Log::info('Estados obtenidos desde caché', [
                    'countryCode' => $countryCode
                ]);
                return response()->json($cachedStates);
            }

            // Si no está en caché, hacer la petición a Geonames
            $url = self::BASE_URL . '/searchJSON';
            $params = [
                'country' => $countryCode,
                'featureClass' => 'A',
                'featureCode' => 'ADM1',
                'username' => $this->getUsername(),
                'lang' => 'es',
                'maxRows' => 100
            ];

            Log::info('Realizando petición a Geonames para estados', [
                'url' => $url,
                'params' => $params
            ]);

            $response = Http::timeout(10)->get($url, $params);
            
            Log::info('Respuesta de Geonames para estados', [
                'status' => $response->status(),
                'headers' => $response->headers(),
                'body' => $response->body()
            ]);

            if (!$response->successful()) {
                throw new \Exception('Error en la respuesta de Geonames: ' . $response->status());
            }

            $data = $response->json();
            
            if (isset($data['status'])) {
                throw new \Exception('Error de Geonames: ' . ($data['status']['message'] ?? 'Error desconocido'));
            }

            if (!isset($data['geonames']) || !is_array($data['geonames'])) {
                throw new \Exception('Formato de respuesta inválido de Geonames');
            }

            // Guardar en caché
            Cache::put($cacheKey, $data, self::CACHE_TTL);
            
            Log::info('Estados obtenidos exitosamente', [
                'countryCode' => $countryCode,
                'count' => count($data['geonames'])
            ]);

            return response()->json($data);

        } catch (\Exception $e) {
            $this->logError('Error obteniendo estados', [
                'countryCode' => $request->input('countryCode'),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Intentar obtener una lista básica de estados como respaldo
            try {
                Log::info('Intentando obtener lista básica de estados como respaldo', [
                    'countryCode' => $request->input('countryCode')
                ]);

                // Lista básica de estados para Colombia
                if ($request->input('countryCode') === 'CO') {
                    $basicStates = [
                        'geonames' => [
                            ['adminCode1' => 'ANT', 'adminName1' => 'Antioquia'],
                            ['adminCode1' => 'ATL', 'adminName1' => 'Atlántico'],
                            ['adminCode1' => 'BOL', 'adminName1' => 'Bolívar'],
                            ['adminCode1' => 'BOY', 'adminName1' => 'Boyacá'],
                            ['adminCode1' => 'CAL', 'adminName1' => 'Caldas'],
                            ['adminCode1' => 'CAQ', 'adminName1' => 'Caquetá'],
                            ['adminCode1' => 'CAS', 'adminName1' => 'Casanare'],
                            ['adminCode1' => 'CAU', 'adminName1' => 'Cauca'],
                            ['adminCode1' => 'CES', 'adminName1' => 'Cesar'],
                            ['adminCode1' => 'CHO', 'adminName1' => 'Chocó'],
                            ['adminCode1' => 'COR', 'adminName1' => 'Córdoba'],
                            ['adminCode1' => 'CUN', 'adminName1' => 'Cundinamarca'],
                            ['adminCode1' => 'DC', 'adminName1' => 'Distrito Capital de Bogotá'],
                            ['adminCode1' => 'GUA', 'adminName1' => 'Guainía'],
                            ['adminCode1' => 'GUV', 'adminName1' => 'Guaviare'],
                            ['adminCode1' => 'HUI', 'adminName1' => 'Huila'],
                            ['adminCode1' => 'LAG', 'adminName1' => 'La Guajira'],
                            ['adminCode1' => 'MAG', 'adminName1' => 'Magdalena'],
                            ['adminCode1' => 'MET', 'adminName1' => 'Meta'],
                            ['adminCode1' => 'NAR', 'adminName1' => 'Nariño'],
                            ['adminCode1' => 'NSA', 'adminName1' => 'Norte de Santander'],
                            ['adminCode1' => 'PUT', 'adminName1' => 'Putumayo'],
                            ['adminCode1' => 'QUI', 'adminName1' => 'Quindío'],
                            ['adminCode1' => 'RIS', 'adminName1' => 'Risaralda'],
                            ['adminCode1' => 'SAP', 'adminName1' => 'San Andrés y Providencia'],
                            ['adminCode1' => 'SAN', 'adminName1' => 'Santander'],
                            ['adminCode1' => 'SUC', 'adminName1' => 'Sucre'],
                            ['adminCode1' => 'TOL', 'adminName1' => 'Tolima'],
                            ['adminCode1' => 'VAC', 'adminName1' => 'Valle del Cauca'],
                            ['adminCode1' => 'VAU', 'adminName1' => 'Vaupés'],
                            ['adminCode1' => 'VID', 'adminName1' => 'Vichada']
                        ]
                    ];
                    
                    Cache::put($cacheKey, $basicStates, self::CACHE_TTL);
                    
                    Log::info('Lista básica de estados cargada como respaldo', [
                        'countryCode' => 'CO',
                        'count' => count($basicStates['geonames'])
                    ]);
                    
                    return response()->json($basicStates);
                }

                throw new \Exception('No hay lista de respaldo disponible para este país');
            } catch (\Exception $fallbackError) {
                $this->logError('Error en respaldo de estados', [
                    'countryCode' => $request->input('countryCode'),
                    'error' => $fallbackError->getMessage()
                ]);
                
                return response()->json([
                    'error' => 'Error obteniendo estados',
                    'details' => $e->getMessage()
                ], 500);
            }
        }
    }

    public function getCities(Request $request)
    {
        try {
            $countryCode = $request->input('countryCode');
            $stateCode = $request->input('stateCode');

            Log::info('Iniciando obtención de ciudades desde Geonames', [
                'countryCode' => $countryCode,
                'stateCode' => $stateCode,
                'request_params' => $request->all()
            ]);

            if (!$countryCode || !$stateCode) {
                throw new \Exception('Country code and state code are required');
            }

            // Intentar obtener del caché primero
            $cacheKey = "geonames_cities_{$countryCode}_{$stateCode}";
            $cachedCities = Cache::get($cacheKey);
            if ($cachedCities) {
                Log::info('Ciudades obtenidas desde caché', [
                    'countryCode' => $countryCode,
                    'stateCode' => $stateCode,
                    'count' => count($cachedCities['geonames'] ?? [])
                ]);
                return response()->json($cachedCities);
            }

            // Primero obtener información del estado para asegurar el código correcto
            $stateUrl = self::BASE_URL . '/searchJSON';
            $stateParams = [
                'country' => $countryCode,
                'featureClass' => 'A',
                'featureCode' => 'ADM1',
                'adminCode1' => $stateCode,
                'username' => $this->getUsername(),
                'lang' => 'es',
                'maxRows' => 1
            ];

            Log::info('Verificando código administrativo del estado', [
                'url' => $stateUrl,
                'params' => $stateParams
            ]);

            $stateResponse = Http::timeout(10)->get($stateUrl, $stateParams);
            $stateData = $stateResponse->json();

            Log::info('Respuesta de verificación de estado', [
                'status' => $stateResponse->status(),
                'data' => $stateData
            ]);

            // Hacer la petición a Geonames para las ciudades
            $url = self::BASE_URL . '/searchJSON';
            $params = [
                'country' => $countryCode,
                'adminCode1' => $stateCode,
                'featureClass' => 'P',
                'featureCode' => ['PPL', 'PPLA', 'PPLA2', 'PPLA3', 'PPLA4'], // Incluir diferentes tipos de ciudades
                'username' => $this->getUsername(),
                'lang' => 'es',
                'maxRows' => 1000,
                'style' => 'FULL'
            ];

            Log::info('Realizando petición a Geonames para ciudades', [
                'url' => $url,
                'params' => $params
            ]);

            $response = Http::timeout(30)->get($url, $params);
            
            Log::info('Respuesta de Geonames para ciudades', [
                'status' => $response->status(),
                'headers' => $response->headers(),
                'body' => $response->body()
            ]);

            if (!$response->successful()) {
                throw new \Exception('Error en la respuesta de Geonames: ' . $response->status());
            }

            $data = $response->json();
            
            if (isset($data['status'])) {
                throw new \Exception('Error de Geonames: ' . ($data['status']['message'] ?? 'Error desconocido'));
            }

            if (!isset($data['geonames']) || !is_array($data['geonames'])) {
                throw new \Exception('Formato de respuesta inválido de Geonames');
            }

            // Filtrar y ordenar las ciudades
            $cities = collect($data['geonames'])
                ->filter(function($city) use ($stateCode) {
                    // Asegurarse de que la ciudad tiene un nombre, ID y pertenece al estado correcto
                    return !empty($city['name']) && 
                           !empty($city['geonameId']) && 
                           (!empty($city['adminCode1']) && $city['adminCode1'] === $stateCode);
                })
                ->sortBy('name')
                ->values()
                ->all();

            Log::info('Ciudades filtradas', [
                'total_antes_filtro' => count($data['geonames']),
                'total_despues_filtro' => count($cities),
                'stateCode' => $stateCode,
                'primeras_ciudades' => array_slice($cities, 0, 5)
            ]);

            $data['geonames'] = $cities;

            // Guardar en caché
            Cache::put($cacheKey, $data, self::CACHE_TTL);
            
            Log::info('Ciudades obtenidas exitosamente', [
                'countryCode' => $countryCode,
                'stateCode' => $stateCode,
                'count' => count($cities)
            ]);

            return response()->json($data);

        } catch (\Exception $e) {
            $this->logError('Error obteniendo ciudades', [
                'countryCode' => $request->input('countryCode'),
                'stateCode' => $request->input('stateCode'),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Error obteniendo ciudades',
                'details' => $e->getMessage()
            ], 500);
        }
    }
} 