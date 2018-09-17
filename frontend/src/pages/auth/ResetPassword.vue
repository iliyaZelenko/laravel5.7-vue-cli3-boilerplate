<template>
  <v-container fill-height>
    <v-layout fill-height justify-center align-center>
      <v-flex xs12 sm8 md6 lg4 xl3>
        <v-card class="elevation-10 app-border-all-round">
          <v-toolbar card prominent>
            <v-layout class="display-1" justify-center>
                Какой пароль хотите?
            </v-layout>
          </v-toolbar>
          <v-card-text>

            <!-- <div class="display-1 text-xs-center mb-3">
              Какой пароль хотите?
            </div> -->


            <form @submit.prevent="submit" @keydown.enter="submit" autocomplete="on">
              <v-text-field
                v-model="form.password"
                v-validate="validatorRules.password"
                :error-messages="errors.collect('password')"
                :type="passwordShow ? 'text' : 'password'"
                :append-icon="passwordShow ? 'visibility_off' : 'visibility'"
                @click:append="passwordShow = !passwordShow"
                data-vv-name="password"
                prepend-icon="security"
                label="Новый пароль"
                required
              />
            </form>

          </v-card-text>
          <v-card-actions class="px-3 pb-3">

            <v-btn
              @click="submit"
              color="primary"
              :disable="btnDisabled"
              :loading="btnLoading"
              large block
            >
              <v-icon left>save</v-icon>
              Сохранить
            </v-btn>

          </v-card-actions>
        </v-card>
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>
import { mapActions } from 'vuex'
import validatorMixin from '@/tools/mixins/validator'

export default {
  mixins: [validatorMixin],
  data: () => ({
    form: {
      password: null
    },
    btnLoading: false,
    passwordShow: true
  }),
  methods: {
    async submit () {
      if (await this.validateByMixin(this.form)) {
        const { email, token } = this.$route.params

        this.btnLoading = true
        await this.resetPassword({
          password: this.form.password,
          email,
          token
        })
        this.btnLoading = false
      }
    },
    ...mapActions('auth', [
      'resetPassword'
    ])
  },
  computed: {
    btnDisabled () {
      return !!this.errors.items.length
    }
  }
}
</script>
