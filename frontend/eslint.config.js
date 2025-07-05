import pluginVue from 'eslint-plugin-vue';

export default [
  // Extends the recommended rules for ESLint and Vue
  ...pluginVue.configs['flat/recommended'],

  {
    // Rules specifically for your project
    rules: {},
    // Specify global variables for your environment
    languageOptions: {
      globals: {
        node: true,
        browser: true,
      },
    },
  },
];