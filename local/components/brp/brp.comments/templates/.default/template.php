<div id="comments">
    <h4 class="text-left">Комментарии ({{comments.length}}): </h4>
    <div class="comments">
        <transition-group name="fade">
            <div v-for="comment in comments" class="comments__item panel panel-default"
                 :key="comment.ID" :id="'cm_'+comment.ID"
                 :style="{ marginLeft: comment.LEVEL*30 + 'px' }">

                <div class="panel-heading">
                    <strong>{{comment.UF_FIO}}</strong> <span v-if="comment.UF_USER_ID<1">(анонимно)</span> <span class="text-muted">{{comment.UF_DATE}}</span>
                </div>
                <div class="panel-body">
                    {{comment.UF_TEXT}}
                    <div class="comments__buttons">
                        <p class="text-left">
                            <a href="#" @click.prevent="editForm(comment)" class="btn btn-default btn-sm">
                                Ответить
                            </a>
                        </p>
                    </div>
                </div>

                <form v-if="comment.editMode" @submit.prevent="addComment" class="form comments__add_form" method="post">
                    <div v-if="errorForm" class="alert alert-danger">{{errorForm}}</div>
                    <input type="hidden" value="Y" name="add">
                    <? if (!$USER->IsAuthorized()): ?>
                        <input class="form-control" type="text" v-model="addForm.fio" placeholder="Ф.И.О.">
                    <? endif ?>
                    <textarea class="form-control" v-model="addForm.text" placeholder="Текст комментария"
                              rows="5"></textarea>
                    <button class="btn btn-success" type="submit">Добавить</button>
                </form>

            </div>
        </transition-group>
        <div v-if="comments.length<1" class="comments__none">
            Комментариев пока нет...
        </div>
    </div>
    <a class="btn btn-info" @click="addNew=true;addForm.parent_id=0">Добавить комментарий</a>
    <transition name="fade">
        <form v-if="addNew" @submit.prevent="addComment" method="post" class="comments__add_form">
            <input type="hidden" value="Y" name="add">
            <div v-if="errorForm" class="alert alert-danger">{{errorForm}}</div>
            <? if (!$USER->IsAuthorized()): ?>
                <input class="form-control" type="text" v-model="addForm.fio" placeholder="Ф.И.О.">
            <? endif ?>
            <textarea class="form-control" v-model="addForm.text" placeholder="Текст комментария" cols="30"
                      rows="10"></textarea>

            <button class="btn btn-success" type="submit">Добавить</button>
        </form>
    </transition>

</div>
<script type="text/javascript">
    var appComments = new Vue({
        el: '#comments',
        data: {
            addNew: false,
            block: '<?=$arParams['BLOCK_COMMENTS']?>',
            comments: [],
            errorForm: '',
            addForm: {
                add: 'Y',
                block: '<?=$arParams['BLOCK_COMMENTS']?>',
                ajax: 'Y',
                fio: '',
                text: '',
                parent_id: '0'
            }
        },
        methods: {
            editForm: function (comment) {
                this.$data.comments.forEach(function (elem) {
                    elem.editMode = false;
                })
                comment.editMode = true;
                this.$data.addForm.parent_id = comment.ID;
                this.clearForm();
            },
            addComment: function () {
                axios.get('', {params: this.$data.addForm}).then(function (response) {
                    if (response.data.STATUS){
                        appComments.getComments();
                        appComments.clearForm();
                    }
                    else
                        appComments.errorForm = response.data.MESSAGE
                });

            },
            getComments: function () {
                axios.get('', {params: {'ajax': 'Y', 'block': this.$data.block}}).then(function (response) {
                    appComments.comments = response.data.ITEMS;
                });
            },
            clearForm: function () {
                this.$data.addForm.text = '';
                this.$data.errorForm = '';
                this.$data.addNew = false;
            }
        },
        created: function () {
            this.getComments();
        }
    })
</script>