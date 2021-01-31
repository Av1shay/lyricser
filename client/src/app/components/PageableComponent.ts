
export default class PageableComponent {
  page: number = 0;
  cursors: Array<string | number> = [];

  protected getNextCursor(page: number) {
    let nextCursor;
    const prev = page < this.page;

    this.page = page;

    if (prev) {
      this.cursors.pop();
      const len = this.cursors.length;

      if (len > 1) {
        nextCursor = this.cursors[len - 2];
      } else {
        nextCursor = null;
      }
    } else {
      nextCursor = this.cursors[this.cursors.length - 1];
    }

    return nextCursor;
  }

  protected shouldPushCursor(cursor: string | number) {
    const cursorsLen = this.cursors.length;
    return cursor && (cursorsLen === 0 || this.cursors[cursorsLen - 1] !== cursor);
  }

}
