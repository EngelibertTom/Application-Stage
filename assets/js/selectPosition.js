const TYPE_NURSERY = 'nursery';
const TYPE_GREENHOUSE = 'greenhouse';
const TYPE_TABLE = 'table';
const TYPE_SEGMENT = 'segment';
const TYPE_COLUMN = 'tableColumn';
const TYPE_ROW = 'columnRow';

class SelectPosition {

    constructor(conf) {
        this.$selectHolder = conf.selectHolder;
        this.$parentRowHolder = this.$selectHolder.parents('.row').first();
        this.$parentColHolder = this.$selectHolder.parents('div[class^="col-md"]').first();
        this.initType = this.$selectHolder.data('type');
        this.initLoadType = this.$selectHolder.data('load');
        this.classColSelect = 'col-md-3';
        this.contentHolder = '';
        this.conf = conf;

        this.defaultValue = null;

        if (typeof conf.selectHolder === "undefined") {
            console.error('Select non trouvé');
        } else {
            this.init();
        }
    }

    init()
    {
        const SelectPosition = this;

        if (this.$parentColHolder.length) {
            this.classColSelect = this.$parentColHolder.attr('class');
        }

        this.contentHolder = this.$parentColHolder;
        this.$parentColHolder.remove();

        this.loadData(TYPE_NURSERY, [], function (selectOptions) {

            if (selectOptions.length) {
                let id = SelectPosition.$selectHolder.data('id');

                if (id)
                {
                    SelectPosition.loadValueDefault(id, function () {
                        SelectPosition.generateSelect('nursery', selectOptions);
                        SelectPosition.changeValueSelect(TYPE_NURSERY, SelectPosition.defaultValue.nursery, true);
                    });
                } else {
                    SelectPosition.generateSelect('nursery', selectOptions);
                    SelectPosition.changeValueSelect(TYPE_NURSERY, selectOptions[0].id);
                }
            }
        });
    }

    loadValueDefault(id, callback)
    {
        const SelectPosition = this;

        $.ajax({
            method: "POST",
            url: "/admin/positions/load",
            dataType: "json",
            data: { type: SelectPosition.initLoadType, id: id }
        }).done(function( data ) {
            SelectPosition.defaultValue = data;

            if (callback) {
                callback();
            }
        });
    }

    /**
     * Génère un select d'un type donné.
     *
     * @param type
     * @param selectOptions
     */
    generateSelect(type, selectOptions)
    {
        let selectExist = this.$parentRowHolder.find('select[data-type="'+type+'"]');

        if (type !== this.initType)
        {
            // Si la select n'existe pas déjà.
            if (selectExist.length <= 0)
            {
                const SelectPosition = this;

                let colSelect = document.createElement('div');
                colSelect.className = this.classColSelect;

                let formGroup = document.createElement('div');
                formGroup.className = 'form-group bmd-form-group is-filled';

                let labelSelect = document.createElement('label');
                labelSelect.className = 'required bmd-label-floating';
                let textLabel = document.createTextNode(this.labelType(type));
                labelSelect.append(textLabel);

                let select = document.createElement('select');
                select.className = 'form-control';
                select.dataset.type = type;

                this.loadOption(select, selectOptions);
                this.defaultValueOption(type, select);

                formGroup.append(labelSelect);
                formGroup.append(select);
                colSelect.append(formGroup);

                this.$parentRowHolder.append(colSelect);

                if (type !== TYPE_ROW)
                {
                    $(select).on('change', function (e) {
                        SelectPosition.changeValueSelect(select.dataset?.type, e.target.value, true);
                    })
                }
            } else {
                this.loadOption(selectExist, selectOptions)
            }
        } else {
            this.$parentRowHolder.append(this.contentHolder);
            this.loadOption(this.contentHolder.find('select'), selectOptions);
            this.defaultValueOption(type, this.contentHolder.find('select')[0]);
        }
    }

    loadOption(select, options)
    {
        if (select.length)
        {
            select.find('option').remove().end();
        }

        $.each(options, function (key, item)
        {
            let option = document.createElement('option');
            option.value = item.id;
            let textOption = document.createTextNode(item.name);
            option.append(textOption);
            select.append(option);
        });
    }

    labelType(type)
    {
        let label = '';

        switch (type) {
            case TYPE_NURSERY:
                label = 'Pépinière';
                break;

            case TYPE_GREENHOUSE:
                label = 'Serre';
                break;

            case TYPE_TABLE:
                label = 'Table';
                break;

            case TYPE_SEGMENT:
                label = 'Segment';
                break;

            case TYPE_COLUMN:
                label = 'Colonne';
                break;

            case TYPE_ROW:
                label = 'Rang';
                break;
        }

        return label;
    }

    /**
     * Gère les actions au changement de valeur des listes déroulantes.
     *
     * @param type
     * @param val
     * @param event
     */
    changeValueSelect(type, val, event = false)
    {
        const SelectPosition = this;

        if (type === undefined) type = TYPE_NURSERY;

        switch (type) {
            case TYPE_NURSERY:
                this.loadData(TYPE_GREENHOUSE, { nursery: val }, function (selectOptions) {
                    if (event && SelectPosition.defaultValue)
                    {
                        SelectPosition.defaultValue.greenhouse = selectOptions[0].id;
                    }

                    SelectPosition.generateSelect(TYPE_GREENHOUSE, selectOptions);

                    if (selectOptions.length) {
                        if (event || !SelectPosition.defaultValue)
                        {
                            SelectPosition.changeValueSelect(TYPE_GREENHOUSE, selectOptions[0].id, true);
                        } else {
                            SelectPosition.changeValueSelect(TYPE_GREENHOUSE, SelectPosition.defaultValue.greenhouse);
                        }
                    } else {
                        SelectPosition.$parentRowHolder.find('select[data-type="greenhouse"]').parent().parent().remove();
                        SelectPosition.$parentRowHolder.find('select[data-type="table"]').parent().parent().remove();
                    }
                });
                break;

            case TYPE_GREENHOUSE:
                if (this.initType !== TYPE_GREENHOUSE) {
                    this.loadData(TYPE_TABLE, { 'greenhouse.id': val }, function (selectOptions) {
                        if (event && SelectPosition.defaultValue && selectOptions[0])
                        {
                            SelectPosition.defaultValue.table = selectOptions[0].id;
                        }

                        SelectPosition.generateSelect(TYPE_TABLE, selectOptions);

                        if (selectOptions.length) {
                            if (event || !SelectPosition.defaultValue)
                            {
                                if (selectOptions[0])
                                {
                                    SelectPosition.changeValueSelect(TYPE_TABLE, selectOptions[0].id, true);
                                }
                            } else {
                                SelectPosition.changeValueSelect(TYPE_TABLE, SelectPosition.defaultValue.table);
                            }
                        } else {
                            SelectPosition.$parentRowHolder.find('select[data-type="table"]').parent().parent().remove();
                        }
                    });
                }
                break;

            case TYPE_TABLE:
                if (this.initType !== TYPE_TABLE) {
                    this.loadData(TYPE_SEGMENT, { 'cultureTable.id': val }, function (selectOptions) {
                        if (event && SelectPosition.defaultValue && selectOptions[0])
                        {
                            SelectPosition.defaultValue.segment = selectOptions[0].id;
                        }

                        SelectPosition.generateSelect(TYPE_SEGMENT, selectOptions);

                        if (selectOptions.length) {
                            if (event || !SelectPosition.defaultValue)
                            {
                                if (selectOptions[0])
                                {
                                    SelectPosition.changeValueSelect(TYPE_SEGMENT, selectOptions[0].id, true);
                                }
                            } else {
                                SelectPosition.changeValueSelect(TYPE_SEGMENT, SelectPosition.defaultValue.segment);
                            }
                        } else {
                            SelectPosition.$parentRowHolder.find('select[data-type="segment"]').parent().parent().remove();
                        }
                    });
                }

                break;

            case TYPE_SEGMENT:
                if (this.initType !== TYPE_SEGMENT) {
                    this.loadData(TYPE_COLUMN, { 'segment.id': val }, function (selectOptions) {

                        if (event && SelectPosition.defaultValue && selectOptions[0])
                        {
                            SelectPosition.defaultValue.tableColumn = selectOptions[0].id;
                        }

                        SelectPosition.generateSelect(TYPE_COLUMN, selectOptions);

                        if (selectOptions.length) {
                            if (event || !SelectPosition.defaultValue)
                            {
                                if (selectOptions[0])
                                {
                                    SelectPosition.changeValueSelect(TYPE_COLUMN, selectOptions[0].id, true);
                                }
                            } else {
                                SelectPosition.changeValueSelect(TYPE_COLUMN, SelectPosition.defaultValue.tableColumn);
                            }
                        } else {
                            SelectPosition.$parentRowHolder.find('select[data-type="tableColumn"]').parent().parent().remove();
                        }
                    });
                }

                break;

            case TYPE_COLUMN:
                if (this.initType !== TYPE_COLUMN) {
                    this.loadData(TYPE_ROW, { 'tableColumn.id': val }, function (selectOptions) {
                        SelectPosition.generateSelect(TYPE_ROW, selectOptions);

                        if (event || !SelectPosition.defaultValue) {
                            if (selectOptions[0])
                            {
                                SelectPosition.changeValueSelect(TYPE_ROW, selectOptions[0].id);
                            }
                        } else {
                            SelectPosition.changeValueSelect(TYPE_ROW, SelectPosition.defaultValue.tableColumn);
                        }
                    });
                }

                break;
        }
    }

    loadData(type, filter = {}, callback = null)
    {
        $.ajax({
            method: "POST",
            url: "/admin/positions/list",
            dataType: "script",
            data: { type: type, filter: filter }
        }).done(function( list ) {
            list = jQuery.parseJSON(list);

            if (callback) {
                callback(list);
            }
        });
    }

    /**
     * Charge les données par défaut
     *
     * @param type
     * @param select
     */
    defaultValueOption(type, select)
    {
        if (this.defaultValue)
        {
            switch (type) {
                case TYPE_NURSERY:
                    select.value = this.defaultValue.nursery;
                    break;

                case TYPE_GREENHOUSE:
                    select.value = this.defaultValue.greenhouse;
                    break;

                case TYPE_TABLE:
                    select.value = this.defaultValue.table;
                    break;

                case TYPE_SEGMENT:
                    select.value = this.defaultValue.segment;
                    break;

                case TYPE_COLUMN:
                    select.value = this.defaultValue.tableColumn;
                    break;

                case TYPE_ROW:
                    select.value = this.defaultValue.columnRow;
                    break;
            }
        }
    }
}

let confDefault = {

};

$.fn.extend({
    selectPosition: function (conf = confDefault) {

        if (this.length)
        {
            conf.selectHolder = this;
            new SelectPosition(conf)
        }

    }
});
