<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Preguntas y Respuestas
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="flex justify-end mb-6">
            <a href="{{ route('preguntas.create') }}" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg shadow">
                + Nueva Pregunta
            </a>
        </div>

        <div class="space-y-6">
            @foreach ($preguntas as $pregunta)
            <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
                <!-- Cabecera de la pregunta -->
                <div class="bg-purple-50 px-6 py-4 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <div>
                            <span class="text-xs text-purple-600 font-semibold">#{{ $pregunta->id_pregunta }}</span>
                            <h3 class="text-lg font-semibold text-gray-800">{{ $pregunta->titulo }}</h3>
                        </div>

                        <div class="flex space-x-2">
                            @if(Auth::user()->id_usuario === $pregunta->usuario || Auth::user()->rol === 'Administrador')
                                <a href="{{ route('preguntas.show', $pregunta->id_pregunta) }}" 
                                class="text-blue-500 hover:text-blue-700 text-sm font-medium">
                                    Ver
                                </a>
                            
                                <a href="{{ route('preguntas.edit', $pregunta->id_pregunta) }}" 
                                class="text-indigo-500 hover:text-indigo-700 text-sm font-medium">
                                    Editar
                                </a>
                            @endif

                            @if(Auth::user()->id_usuario === $pregunta->usuario)
                                <form action="{{ route('preguntas.destroy', $pregunta->id_pregunta) }}" method="POST" onsubmit="return confirm('¿Estás seguro?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-medium">
                                        Eliminar
                                    </button>
                                </form>
                            @endif
                        </div>

                    </div>
                </div>
                
                <!-- Cuerpo de la pregunta -->
                <div class="px-6 py-4">
                    <div class="mb-4">
                        <h4 class="text-sm font-semibold text-purple-700 mb-2">Descripción:</h4>
                        <p class="text-gray-700">{{ $pregunta->descripcion }}</p>
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-semibold text-purple-700 mb-2">Respuesta:</h4>
                        @if($pregunta->respuesta)
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                                <p class="text-gray-700">{{ $pregunta->respuesta }}</p>
                            </div>
                        @else
                            <p class="text-gray-500 italic">Aún no hay respuesta</p>
                        @endif
                    </div>
                </div>
                
                <!-- Pie de la pregunta (opcional) -->
                <div class="bg-gray-50 px-6 py-3 text-xs text-gray-500 border-t border-gray-200">
                    Creado el {{ $pregunta->fecha_pub->format('d/m/Y H:i') }}
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $preguntas->links() }}
        </div>
    </div>
</x-app-layout>