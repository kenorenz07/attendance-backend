<script>
export default {
    data: vm => ({
        nameRule : [
            v => !!v || 'Name is required',
            v => (v && v.length <= 10) || 'Name must be less than 10 characters',
        ],
        emailRule: [ 
            v => !!v || 'Email is required', 
            v => /.+@.+/.test(v) || 'E-mail must be valid' 
        ],
        usernameRule : [
            v => !!v || 'Username is required',
        ],
        first_nameRule : [
            v => !!v || 'First name is required',
        ],
        middle_nameRule : [
            v => !!v || 'Middle is required',
        ],
        last_nameRule : [
            v => !!v || 'Last is required',
        ],
        passwordRule : [
            v => !!v || 'Password is required',
        ],
        rfid_numberRule : [
            v => !!v || 'RFID is required',
        ],
    }),
    methods: {
      
    }
};
</script>
