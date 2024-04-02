<template>
	<div class="card relative z-2">
        <Menubar
            :model="items"
            :pt="{
                action: ({ props, state, context }) => ({
                    class: context.active ? 'bg-primary-200 border-round-sm' : context.focused ? 'bg-primary-300 border-round-sm' : undefined
                })
            }"
        />
	</div>
</template>
<script>
export default {
	name: 'appsubmenu',
	props: {
		items: Array,
		root: {
			type: Boolean,
			default: false
		}
	},
	data() {
		return {
			activeIndex : null
		}
	},
	methods: {
		onMenuItemClick(event, item, index) {
			if (item.disabled) {
				event.preventDefault();
				return;
            }

            if (!item.to && !item.url) {
                event.preventDefault();
            }

			//execute command
			if (item.command) {
                item.command({originalEvent: event, item: item});
			}

			this.activeIndex = index === this.activeIndex ? null : index;

			this.$emit('menuitem-click', {
				originalEvent: event,
				item: item
			});
		},
		visible(item) {
			return (typeof item.visible === 'function' ? item.visible() : item.visible !== false);
		}
	}
}
</script>
