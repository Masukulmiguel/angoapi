<template>
  <footer class="bg-ango-dark dark:bg-gray-950 text-gray-400">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
        <div class="md:col-span-1">
          <div class="mb-4">
            <img src="/logo-fundo-escuro.png" alt="AngoAPI" class="h-10 w-auto" />
          </div>
          <p class="text-sm leading-relaxed">
            Plataforma de APIs para o Ecossistema Digital Angolano. Integre serviços de validação e dados de forma simples e segura.
          </p>
        </div>

        <div>
          <h4 class="text-white font-semibold mb-4">Plataforma</h4>
          <ul class="space-y-2 text-sm">
            <li><router-link to="/planos" class="hover:text-white transition-colors">Planos e Preços</router-link></li>
            <li><router-link to="/docs" class="hover:text-white transition-colors">Documentação</router-link></li>
            <li><router-link to="/docs" class="hover:text-white transition-colors">APIs Disponíveis</router-link></li>
            <li><a href="#" class="hover:text-white transition-colors">Status do Sistema</a></li>
          </ul>
        </div>

        <div>
          <h4 class="text-white font-semibold mb-4">Empresa</h4>
          <ul class="space-y-2 text-sm">
            <li><a href="#" class="hover:text-white transition-colors">Sobre Nós</a></li>
            <li><a href="#" class="hover:text-white transition-colors">Contacto</a></li>
            <li><a href="#" class="hover:text-white transition-colors">Termos de Uso</a></li>
            <li><a href="#" class="hover:text-white transition-colors">Política de Privacidade</a></li>
          </ul>
        </div>

        <div>
          <h4 class="text-white font-semibold mb-4">Contacto</h4>
          <ul class="space-y-3 text-sm">
            <li class="flex items-center gap-2">
              <svg class="w-4 h-4 text-ango-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
              <span>935603163</span>
            </li>
            <li class="flex items-center gap-2">
              <svg class="w-4 h-4 text-ango-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
              <span>info@angoapi.co.ao</span>
            </li>
          </ul>
          <div class="flex gap-3 mt-6">
            <a href="#" class="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center hover:bg-ango-red transition-colors">
              <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
            </a>
            <a href="#" class="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center hover:bg-ango-red transition-colors">
              <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
            </a>
          </div>
        </div>
      </div>
    </div>
    <div class="border-t border-white/10">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 text-center text-sm">
        &copy; {{ new Date().getFullYear() }} AngoAPI. Todos os direitos reservados.
      </div>
    </div>
  </footer>
</template>
