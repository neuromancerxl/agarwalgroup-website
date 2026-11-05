(function (blocks, editor, components, element, server, i18n) {
  "use strict";

  const create = element.createElement;
  const translate = i18n.__;
  const ServerSideRender = server.ServerSideRender || server.default || server;

  blocks.registerBlockType("agarwal/paper", {
    edit: function (props) {
      const selector = create(components.ComboboxControl, {
        label: translate("Publication", "agarwal-group"),
        value: props.attributes.paperId,
        options: window.agarwalPaperOptions || [],
        onChange: function (paperId) {
          props.setAttributes({ paperId: paperId || "" });
        }
      });

      return create("div", editor.useBlockProps(),
        create(editor.InspectorControls, null,
          create(components.PanelBody, { title: translate("Publication reference", "agarwal-group") },
            selector,
            create(components.ToggleControl, {
              label: translate("Compact research link", "agarwal-group"),
              checked: props.attributes.compact,
              onChange: function (compact) { props.setAttributes({ compact: compact }); }
            }),
            create("p", null, translate("Move or remove this reference using the normal block controls. Citation details come from pubbase.json in this first release.", "agarwal-group"))
          )
        ),
        props.attributes.paperId
          ? create(ServerSideRender, { block: "agarwal/paper", attributes: props.attributes })
          : create(components.Placeholder, { label: translate("Publication reference", "agarwal-group"), icon: "book" }, selector)
      );
    },
    save: function () { return null; }
  });

  blocks.registerBlockType("agarwal/publications", {
    edit: function () {
      return create("div", editor.useBlockProps(),
        create("div", { className: "agarwal-editor-note" },
          translate("Complete publication catalog · 10 per page. Search and filters work in the website preview.", "agarwal-group")
        ),
        create(components.Disabled, null,
          create(ServerSideRender, { block: "agarwal/publications", attributes: {} })
        )
      );
    },
    save: function () { return null; }
  });
})(window.wp.blocks, window.wp.blockEditor, window.wp.components, window.wp.element, window.wp.serverSideRender, window.wp.i18n);
